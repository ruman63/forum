let user = window.App.user;
module.exports = {
    updateProfile(profileUser){
        return user.id === profileUser.id
    },
    updateReply(reply) {
        return reply.user_id === user.id;
    },
    updateThread(thread) {
        return thread.user_id === user.id;
    }
}