<template>
    <button type="button" :class="`calBtn ${additionalCssSelector}`" @click="callback(value)">{{ value }}</button>
</template>

<script>
export default{
    props: {
        callback: {
            default: () => {},
            required: true
        },
        value: {
            default: '',
            required: true,
        },
        additionalCssSelector: {
            default: '',
        }
    }
}
</script>
