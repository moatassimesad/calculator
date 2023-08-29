<template>
    <div>
        <input type="text" :value="value" class="calInput" readonly />
    </div>
</template>

<script>
export default{
    props: {
        value: {
            default: '',
            required: true,
        },
    }
}
</script>
