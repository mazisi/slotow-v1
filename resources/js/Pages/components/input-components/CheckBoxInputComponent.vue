<template>

    <div :class="column" class=" columns">
        <div class=" form-switch d-flex ps-0 ms-0  is-filled">
            <label class="form-check-label mb-0 text-body text-truncate">{{ label }}</label>
            <input id="active-checkbox" type="checkbox" :value="value" :checked="isChecked" />
        </div>
    </div>

</template>

<script>

    export default{
        props:{
            column: String,
            label: String,
            isChecked: Boolean,
            value: Number
        }
    }

</script>