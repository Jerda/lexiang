<template>
    <span>登录中...</span>
</template>

<script>
    export default {
        mounted() {
            this.$store.dispatch('loginRequest', {username: 'oi5N40led-7GHbrxULUdTwX-U7tM', password: 'oi5N40led-7GHbrxULUdTwX-U7tM'}).then(response => {
                // this.$router.push({name:'index'});
            }).catch(error => {
                this.$message.error(error.response.data.message);
            });
        }
    }
</script>
