<template>
    <div class="error-popup">
        <div class="error-content">
            <h2>Error!</h2>
            <p>{{ alert }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        alert: {
            required: true,
            default: ''
        }
    }
}
</script>
