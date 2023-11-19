<template>
    
<div :class="column" class=" columns">
    <div class="input-group input-group-outline null is-filled ">
        <label :for="input_id" class="form-label">{{ label }}</label>

        <input :id="input_id" 
        :value="value" 
        :type="inputType" 
        :required="required"
        :disabled="disabled" 
        class="form-control form-control-default" 
        @input="$emit('update:modelValue', $event.target.value)" />
    </div>
    <div v-if="errors" class="text-danger">{{ errors }}</div>
</div>

</template>
<script>


export default{
props:{
    col: Number,
    input_id: String,
    value: String,
    column: String,
    errors: Object,
    label: String,
    required: Boolean,
    inputType: String,
    disabled: Boolean
}
}
</script>