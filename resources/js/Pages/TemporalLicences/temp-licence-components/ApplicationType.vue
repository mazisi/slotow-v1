<template>
        <div class="col-4 columns">
        <div class="input-group input-group-outline null is-filled ">
        
        <select class="form-control form-control-default" required @change="$emit('update:modelValue', $event.target.value)" >
            <option :value="''" disabled selected >Application Type</option>
            <option value="Off-Consumption">Off-Consumption</option>
            <option value="On-Consumption">On-Consumption</option>
        </select>
        </div>
        <div v-if="errors" class="text-danger">{{ errors }}</div>
        </div>
</template>

<script>
export default {
props: {
    //
}
}
</script>