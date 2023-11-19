<template>
  <div :class="column" class=" columns">
      <div class="input-group input-group-outline null is-filled">
          <label :for="label" class="form-label">{{ label }}</label>
          <select @change="$emit('update:modelValue', $event.target.value)"
          :required="required"
          :id="label" 
          class="form-control form-control-default">
                <option :value="''" disabled selected>{{ defaultDisabledText }}</option>
               <option v-for='dropdown in dropdownList' :key="dropdown" :value=dropdown > {{ dropdown }}</option>
          </select>
      </div>
   <div v-if="errors" class="text-danger">{{ errors }}</div>
</div>

</template>

<script>
export default{
  props: {
      errors: Object,
      required: Boolean,
      dropdownList: Object,
      label: String,
      column: String,
      value: String,
      defaultDisabledText: String
  }
}
</script>