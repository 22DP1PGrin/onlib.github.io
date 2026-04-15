<script setup>
    import { ref, watch, nextTick } from 'vue'
    import { router, useForm } from '@inertiajs/vue3'
    import { route } from 'ziggy-js'
    import axios from 'axios'

    // Komponenta ievaddati
    const props = defineProps({
        comments: {
            type: Array,
            default: () => []
        },
        commentsCount: {
            type: Number,
            default: 0
        },
        authUser: {
            type: Object,
            default: null
        },
        bookId: {
            type: Number,
            required: true
        },

        commentType: {
            type: String,
            validator: (value) => ['user', 'classic'].includes(value)
        }
    })

    // Emit definīcija - lai paziņotu par ziņošanas atvēršanu
    const emit = defineEmits(['openReport'])

    // Stāvokļa mainīgie
    const activeMenu = ref(null)
    const editingCommentId = ref(null)
    const editedContent = ref('')
    const activeReplyId = ref(null)
    const showAllReplies = ref({})
    const selectedComment = ref(null)
    const repliesLimit = 3

    // Izveido formas objektu jauna komentāra iesniegšanai
    const comment = useForm({
        content: ''
    })

    // Izveido formas objektu atbildes  rakstīšanai uz esošu komentāru
    const replyForm = useForm({
        content: '',
        comment_parent_id: null
    })

    // Funkcija datuma formatēšanai
    const formatDate = (date) => {
        return new Date(date).toLocaleString('lv-LV', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        })
    }

    // Funkcija textarea izmēra pielāgošanai
    const resizeTextarea = (el) => {
        el.style.height = "auto" // Atiestata augstumu
        el.style.height = el.scrollHeight + "px"  // Uzstāda jauno augstumu pēc satura
        el.style.overflowY = el.scrollHeight > 200 ? "auto" : "hidden"  // Pievieno scroll joslu ja nepieciešams
    }

    // Automātiska textarea izmēra pielāgošana ievades laikā
    const autoResize = (e) => resizeTextarea(e.target)

    // Izvēlnes atvēršana/aizvēršana
    const toggleMenu = (commentId) => {
        activeMenu.value = activeMenu.value === commentId ? null : commentId
    }

    // Rediģēšanas režīma aktivizēšana
    const startEdit = async (commentItem) => {
        // Saglabā rediģējamā komentāra ID
        editingCommentId.value = commentItem.id
        // Ielādē esošo saturu rediģēšanai
        editedContent.value = commentItem.content
        // Aizver izvēlni un atbildes formu
        activeMenu.value = activeReplyId.value = null

        // Gaida, kamēr DOM tiek atjaunināts
        await nextTick()
        // Pielāgo textarea izmēru
        document.querySelectorAll('.reply').forEach(resizeTextarea)
    }

    // Rediģētā komentāra saglabāšana
    const saveEdit = async (commentId) => {
        // Nosūta PUT pieprasījumu uz serveri ar atjaunināto saturu
        await axios.put(route('comments.update', commentId), {
            content: editedContent.value
        })
        editingCommentId.value = null
        router.reload({ only: ['comments'] })
    }

    // Redzamo atbilžu attēlošana
    const visibleReplies = (commentItem) => {
        if (!commentItem.replies) return [] // Ja nav atbilžu, atgriež tukšu masīvu
        return showAllReplies.value[commentItem.id]
            ? commentItem.replies // Ja izvērsts - rāda visas atbildes
            : commentItem.replies.slice(0, repliesLimit) // Ja nē - rāda tikai pirmās 3
    }

    // Atbildes formas atvēršana
    const openReply = (commentId) => {
        if (activeReplyId.value === commentId) {
            cancelReply()
        } else {
            activeReplyId.value = commentId
            replyForm.comment_parent_id = commentId
        }
    }

    // Atbildes formas aizvēršana un datu notīrīšana
    const cancelReply = () => {
        activeReplyId.value = null  // Aizver formu
        replyForm.reset()           // Notīra ievadītos datus
    }


    // Jauna komentāra iesniegšana
    const submitComment = () => {
        // Izvēlas pareizo ceļas nosaukumu atkarībā no komentāra tipa
        const routeName = props.commentType === 'classic'
            ? 'classic.comments.store'
            : 'user.comments.store'

        // Izvēlas pareizo ceļas parametru atkarībā no tipa
        const routeParams = props.commentType === 'classic'
            ? { classic_book: props.bookId }
            : { user_book: props.bookId }

        // Nosūta komentāru uz serveri
        comment.post(route(routeName, routeParams), {
            preserveScroll: true,
            onSuccess: () => {
                comment.reset()
                comment.comment_parent_id = null
            }
        })
    }

    // Atbildes iesniegšana
    const submitReply = () => {
        // Izvēlas pareizo ceļas nosaukumu atkarībā no komentāra tipa
        const routeName = props.commentType === 'classic'
            ? 'classic.comments.store'
            : 'user.comments.store'

        // Izvēlas pareizo ceļas parametru atkarībā no tipa
        const routeParams = props.commentType === 'classic'
            ? { classic_book: props.bookId }
            : { user_book: props.bookId }

        // Nosūta komentāru uz serveri
        replyForm.post(route(routeName, routeParams), {
            preserveScroll: true,
            onSuccess: () => {
                cancelReply()
            }
        })
    }

    // Dzēšanas modālā loga atvēršana
    const openDeleteModal = (commentItem) => {
        selectedComment.value = commentItem
        // Emitē notikumu vecākkomponentam, lai atvērtu dzēšanas modālo logu
        emit('openDeleteModal', commentItem)
    }

    // Ziņošanas modālā loga atvēršana
    const openCommentReport = (commentItem) => {
        emit('openReport', commentItem)
    }

    // URL parametra 'comment' apstrāde (lai automātiski atvērtu konkrētu komentāru)
    const urlParams = new URLSearchParams(window.location.search)
    const commentId = urlParams.get('comment')

    // Nolūko izmaiņas komentāru sarakstā
    watch(
        () => props.comments,  // Nolūko komentāru masīvu
        async () => {
            if (!commentId) return

            await nextTick() // Gaida DOM atjaunināšanu

            // Mēģina atrast komentāru pēc ID
            let el = document.getElementById('comment-' + commentId)

            if (el) {
                // Ja komentārs atrasts - ritina līdz tam
                el.scrollIntoView({ behavior: 'smooth', block: 'center' })
                return
            }

            // Ja komentārs nav atrasts, meklē vai tas nav atbilde uz kādu komentāru
            const parent = props.comments.find(c =>
                c.replies?.some(r => r.id == commentId)
            )

            // Ja atrasts vecākkomentārs - izvērš tā atbildes
            if (parent) {
                showAllReplies.value = {
                    ...showAllReplies.value,
                    [parent.id]: true
                }

                await nextTick()

                // Mēģina atrast atbildi vēlreiz
                el = document.getElementById('comment-' + commentId)

                if (el) {
                    // Ritina līdz atbildei
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' })
                }
            }
        },
        { immediate: true, deep: true } // Izpilda uzreiz un seko dziļām izmaiņām
    )
</script>
<template>
    <!-- Komentāri -->
    <div class="book-header">
        <h1>
            Komentāri
        </h1>
    </div>

    <!-- Komentāru ievades forma tiek rādīta tikai autentificētam lietotājam -->
    <div class="book-container">
        <div v-if="authUser">
            <form @submit.prevent="submitComment" class="form-group">
                <!-- Komentāra ievades lauks -->
                    <textarea
                        @input="autoResize"
                        v-model="comment.content"
                        rows="3"
                        placeholder="Ievadiet savu komentāru"
                        required
                    ></textarea>

                <!-- Kļūdas paziņojums, ja komentāra saturs nav derīgs -->
                <div v-if="comment.errors.content" class="error-message">
                    {{ comment.errors.content }}
                </div>

                <!-- Poga komentāra nosūtīšanai -->
                <div class="form-actions">
                    <button
                        type="submit"
                        class="submit-btn"
                        :disabled="comment.processing"
                    >
                        Nosūtīt
                    </button>
                </div>
            </form>
        </div>

        <!-- Kopējais komentāru skaits -->
        <h2>{{ commentsCount }} komentāri</h2>

        <!-- Jā nav komentārus -->
        <div v-if="comments.length === 0" class="item">
            <span class="title">Šeit vēl nav pievienotu komentāru.</span>
        </div>

        <!-- Komentāru saraksts -->
        <div v-if="comments?.length" class="comments-list">
            <div
                v-for="comment in comments"
                :key="comment.id"
                class="comment-item"
                :id="'comment-' + comment.id"
            >
                <!-- Komentāra galvene ar lietotāja informāciju -->
                <div class="comment-header">
                    <div class="comment-user-info">
                        <div class="comment-user-row">
                            <div class="avatar-wrapper">
                                <div class="avatar">
                                    <i v-if="!comment.user?.avatar" class="fa profile-icon">&#xf2be;</i>
                                    <img v-else :src="`/storage/${comment.user?.avatar}`" alt="avatar" />
                                </div>
                            </div>

                            <!-- Lietotāja vārds ar saiti uz profilu -->
                            <div class="comment-user-name">
                                <a
                                    :href="route('other.users.watch', { id: comment.user?.id })"
                                    class="author-name"
                                >
                                    {{ comment.user?.nickname }}
                                </a>
                            </div>

                            <!-- Paziņojums, ja komentārs ir rediģēts -->
                            <div v-if="comment.updated_at && comment.created_at !== comment.updated_at" class="comment-time-update">
                                (Rediģēts)
                            </div>
                        </div>

                        <!-- Komentāra izveidošanas laiks -->
                        <div class="comment-time">
                            {{ formatDate(comment.created_at) }}
                        </div>
                    </div>

                    <!-- Izvēlnes darbības -->
                    <div class="comment-menu-wrapper">
                        <button v-if="authUser" class="comment-menu-button"  @click="toggleMenu(comment.id)">
                            ⋮
                        </button>
                        <!-- Rediģēšana pieejama tikai autoram -->
                        <div v-if="activeMenu === comment.id" class="comment-menu">
                            <button
                                v-if="authUser?.id === comment.user?.id"
                                class="menu-item"
                                @click="startEdit(comment)"
                            >
                                Rediģēt
                            </button>

                            <!-- Dzēšana pieejama autoram un administratoriem -->
                            <button v-if="authUser?.id === comment.user?.id|| authUser?.role === 'admin' || authUser?.role === 'superadmin'" class="menu-item" @click="openDeleteModal(comment)">
                                Dzēst
                            </button>

                            <button class="menu-item" @click="openCommentReport(comment)">
                                Ziņot
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Komentāra rediģēšanas forma -->
                <div class="reply-form" v-if="editingCommentId === comment.id">
                    <!-- Teksta laukums rediģēšanai -->
                        <textarea class="reply"
                                  @focus="autoResize"
                                  v-model="editedContent"
                                  required
                                  @input="autoResize"
                                  rows="3">
                        </textarea>

                    <div class="reply-actions">
                        <button @click="saveEdit(comment.id)">
                            Saglabāt
                        </button>

                        <button @click="editingCommentId = null">
                            Atcelt
                        </button>
                    </div>
                </div>

                <!-- Komentāra saturs, ja tas netiek rediģēts -->
                <div class="comment-content" v-else >
                    {{ comment.content }}
                </div>

                <!-- Atbildes poga -->
                <div v-if="authUser && editingCommentId !== comment.id" class="reply-button-wrapper">
                    <button
                        @click="openReply(comment.id)"
                        class="reply-btn"
                    >
                        Atbildēt
                    </button>
                </div>

                <!-- Atbildes ievades forma -->
                <form v-if="activeReplyId === comment.id" @submit.prevent="submitReply" class="reply-form">
                    <!-- Atbildes teksts -->
                    <textarea
                        @input="autoResize"
                        class="reply"
                        v-model="replyForm.content"
                        placeholder="Rakstīt atbildi..."
                        required
                        rows="2"
                    ></textarea>

                    <!-- Kļūda atbildes ievadē -->
                    <div v-if="replyForm.errors.content" class="error">
                        {{ replyForm.errors.content }}
                    </div>

                    <div class="reply-actions">
                        <button
                            type="submit"
                            class="reply-submit-btn"
                        >
                            Nosūtīt
                        </button>
                    </div>
                </form>

                <!-- Atbildes -->
                <div v-if="comment.replies?.length" class="comment-replies">

                    <div v-for="reply in visibleReplies(comment)"
                         :key="reply.id"
                         :id="'comment-' + reply.id"
                         class="comment-reply">

                        <!-- Atbildes galvene ar lietotāja informāciju -->
                        <div class="comment-header">
                            <div class="comment-user-info">
                                <div class="comment-user-row">
                                    <div class="avatar-wrapper">
                                        <div class="avatar">
                                            <i v-if="!reply.user?.avatar" class="fa profile-icon">&#xf2be;</i>
                                            <img v-else :src="`/storage/${reply.user?.avatar}`" alt="avatar" />
                                        </div>
                                    </div>

                                    <div class="comment-user-name">
                                        <a
                                            :href="route('other.users.watch', { id: reply.user?.id })"
                                            class="author-name"
                                        >
                                            {{ reply.user?.nickname }}
                                        </a>
                                    </div>

                                    <div v-if="reply.updated_at && reply.created_at !== reply.updated_at" class="comment-time-update">
                                        (Rediģēts)
                                    </div>
                                </div>

                                <div class="comment-time">
                                    {{ formatDate(reply.created_at) }}
                                </div>
                            </div>

                            <!-- Atbildes izvēlne -->
                            <div class="comment-menu-wrapper">
                                <button v-if="authUser" class="comment-menu-button"  @click="toggleMenu(reply.id)">
                                    ⋮
                                </button>
                                <div v-if="activeMenu === reply.id" class="comment-menu">
                                    <button v-if="authUser?.id === reply.user?.id" class="menu-item" @click="startEdit(reply)">
                                        Rediģēt
                                    </button>
                                    <button v-if="authUser?.id === reply.user?.id || authUser?.role === 'admin' || authUser?.role === 'superadmin'" class="menu-item" @click="openDeleteModal(reply)">
                                        Dzēst
                                    </button>
                                    <button class="menu-item" @click="openCommentReport(reply)">
                                        Ziņot
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Atbildes rediģēšana -->
                        <div class="reply-form" v-if="editingCommentId === reply.id">
                                <textarea class="reply"
                                          @focus="autoResize"
                                          v-model="editedContent"
                                          @input="autoResize"
                                          required
                                          rows="3"
                                ></textarea>

                            <div class="reply-actions">
                                <button @click="saveEdit(reply.id)">
                                    Saglabāt
                                </button>

                                <button @click="editingCommentId = null">
                                    Atcelt
                                </button>
                            </div>
                        </div>

                        <!-- Atbildes saturs -->
                        <div class="comment-content" v-else >
                            {{ reply.content }}
                        </div>
                    </div>
                </div>

                <!-- Skatīt vairāk -->
                <div class="toggle-container">
                    <button v-if="comment.replies?.length > repliesLimit"  class="toggle-btn" @click="showAllReplies[comment.id] = !showAllReplies[comment.id]">
                        {{
                            showAllReplies[comment.id]
                                ? 'Paslēpt'
                                : 'Skatīt vairāk'
                        }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .form-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 5px;
    }

    .success-container h2 {
        margin-bottom: 15px;
        font-size:  1.3rem;
        font-weight: bold;
        color: rgba(26, 16, 8, 0.8);
    }

    .success-container p {
        margin-bottom: 15px;
        color: rgba(26, 16, 8, 0.8);
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-bottom: 10px;
    }

    label {
        font-weight: bold;
        text-align: left;
        color: rgba(26, 16, 8, 0.8); /* Krāsa */
    }

    textarea::placeholder {
        color: rgba(26, 16, 8, 0.42);
        font-size: 1.0rem;
    }

    /* Kļūdas zem ievades lauka */
    .error{
        color: rgb(110, 37, 37);
        font-size: 1rem;
        text-align: left;
        margin-bottom: 5px;
    }

    .form-group select,
    .form-group textarea {
        resize: none;
        overflow: hidden;
        padding: 10px;
        border: 1px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 1rem;
    }

    option{
        font-size: 1rem;
    }

    textarea {
        resize: none;
        overflow: hidden;
        min-height: 40px;
        max-height: 300px;
        width: 100%;
        box-sizing: border-box;
        padding: 10px;
        border: 1px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 1rem;
    }

    textarea:focus,
    input:focus {
        outline: none; /* Noņem apmales fokusa režīmā */
        box-shadow: none; /* Noņem nokrāsu ap laukiem */
        background-color: #ffc8a9; /* Fona krāsa, kad lauks ir fokusēts */
    }

    .book-container {
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmale  */
        background-color: #e4a27c; /* Fona krāsa */
        border-radius: 8px; /* Noapaļoti stūri */
        box-shadow: 0 6px 15px rgba(63, 31, 4, 0.8); /* Ēna */
        padding: 30px; /* Iekšējā atkāpe */
        margin-bottom: 40px; /* Apakšējā ārējā atkāpe */
    }

    .book-header {
        margin-top: 32px; /* Augšējā ārējā atkāpe */
        margin-bottom: 30px; /* Apakšējā ārējā atkāpe */
        text-align: center; /* Teksts centrēts */
    }

    h1  {
        color: rgba(26, 16, 8, 0.8);
        font-size: 1.7rem; /* Fonta izmērs */
        margin-bottom: 20px;
        font-weight: bold; /* Treknraksts */
    }

    h2 {
        font-size: 1.1rem;
        font-weight: bold;
        color: rgba(26, 16, 8, 0.8);
        margin-bottom: 10px;
    }

    .author-name {
        font-weight: bold;
        color: rgba(106, 51, 0, 0.8);
        font-size: 1rem;
    }

    button {
        background-color: #c58667;
        border: 2px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        font-family: Tahoma, Helvetica, sans-serif;
        font-size: 1rem;
        padding: 3px 20px;
        align-items: center;
    }

    .reply-actions {
        gap:5px;
    }

    .reply-actions button{
        padding: 1px 6px;
    }

    .reply-button-wrapper {
        display: flex;
        justify-content: flex-end;
        margin-top: 8px;
    }

    .toggle-container {
        margin-top: 5px;
        display: flex;
        justify-content: center;
    }

    .toggle-btn{
        padding: 2px 10px;
        margin-top: 5px;
    }

    .reply-btn{
        padding: 1px 6px;
        margin-top: -20px;
    }

    .reply{
        margin-top: 7px;
    }

    .reply-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 5px;
    }

    .reply-submit-btn {
        padding: 1px 10px;
    }

    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }

    .item {
        background-color: #ffc8a9;
        border-radius: 8px; /* Noapaļotas malas */
        text-align: center;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 10px; /* Ēna*/
        font-family: Tahoma, Helvetica, sans-serif;
        display: flex; /* Izmanto flexbox režīmu */
        justify-content: space-between; /* Izlīdzina saturu horizontāli */
        align-items: center;
        padding: 10px 15px; /* Piepildījums ap saturu */
        margin-bottom: 10px;
    }

    .title {
        color: rgba(26, 16, 8, 0.8);
        font-family: Tahoma, Helvetica, sans-serif;
        font-size: 1.1rem;
        font-weight: bold;
    }

    .comments-list {
        margin-top: 25px;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .comment-item {
        background-color: #ffd9c6;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(63, 31, 4, 0.8);
    }

    .comment-header {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 10px;
        margin-top: -5px;
        font-size: 1rem;
        justify-content: space-between;
    }

    .comment-menu-wrapper {
        position: relative;
    }

    .comment-menu-button {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: rgba(26, 16, 8, 0.8);
        transition: color 0.3s;
    }

    .comment-menu-button:hover {
        color: rgba(106, 51, 0, 0.8);
        background-color: transparent !important;
    }

    .comment-menu {
        position: absolute;
        right: 0;
        top: 25px;
        background-color: #e4a27c;
        border: 1px solid rgba(26, 16, 8, 0.8);
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(63, 31, 4, 0.8);
        display: flex;
        flex-direction: column;
        min-width: 120px;
        z-index: 50;
    }

    .menu-item {
        background: none;
        border: none;
        padding: 8px 12px;
        text-align: left;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .menu-item:hover {
        background-color: #ffc8a9;
    }

    .avatar-wrapper {
        display: flex;
        justify-content: center; /* Horizontāli centrēts */
    }

    .avatar {
        width: 25px; /* Avatar platums */
        height: 25px; /* Avatar augstums */
        border-radius: 50%; /* Padara avataru pilnīgu apli */
        border: 1px solid rgba(26, 16, 8, 0.8);
        background-color: #e4a27c;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar img {
        width: 100%; /* Attēla platums pilnībā atbilst avataram */
        height: 100%; /* Attēla augstums pilnībā atbilst avataram */
        object-fit: cover; /* Attēls aptver visu aplīti, saglabājot proporcijas */
        border-radius: 50%;
    }
    .comment-user-info {
        display: flex;
        flex-direction:column;
    }

    .comment-user-row{
        display:flex;
        align-items:center;
        gap:8px;
    }

    .comment-user-name a {
        font-weight: bold;
        color: rgba(26, 16, 8, 0.8) !important;
        transition: color 0.3s;
    }

    .comment-user-name a:hover {
        color: rgba(106, 51, 0, 0.8) !important;
    }

    .comment-time,
    .comment-time-update{
        font-size: 0.9rem;
        opacity: 0.7;
        line-height: 1;
    }

    .comment-time{
        margin-top: 5px;
    }

    .comment-time-update{
        margin-left: -5px;
    }

    .comment-content {
        line-height: 1.5;
        word-wrap:break-word;
        font-size: 1rem;
    }

    .comment-replies {
        margin-left: 30px;
        margin-top: 15px;
        padding-left: 15px;
        border-left: 2px solid rgba(26, 16, 8, 0.3);
        word-wrap:break-word
    }

    .comment-reply {
        background-color: #ffc8a9;
        padding: 10px;
        border-radius: 6px;
        margin-top: 8px;
    }

    @media (max-width: 768px) {
        .book-container {
            padding: 20px;
        }
    }

    @media (max-width:500px) {
        .title{
            font-size: 1rem;
        }

        .reply-btn{
            margin-top: -5px;
        }

        .comment-content{
            font-size: 0.9rem;
        }

        .comment-header{
            font-size: 0.9rem;
        }

        .comment-time,
        .comment-time-update{
            font-size: 0.85rem;
        }

        .author-name {
            font-size: 0.9rem;
        }

        h2 {
            font-size: 1rem;
        }

        button {
            font-size: 0.9rem;
            padding: 3px 17px;
        }
}
</style>
