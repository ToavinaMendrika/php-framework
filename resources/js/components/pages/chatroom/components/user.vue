<template>
    <div class="column">
        <div class="bordered">
            <div class="search-wrapper">
                <user-profile :user-id="this.$route.params.id"/>
            </div>
        </div>
    </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import UserProfile from './userProfile'
    export default {
        components: {
            UserProfile
        }
    }
</script>