<template>
  <div class="card z-index-2">
    <div
      class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent"
    >
      <div
        class="border-radius-lg py-3 pe-1"
        :class="`bg-gradient-${color} shadow-${color}`"
      >
        <slot />
      </div>
    </div>
    <div class="card-body">
      <h6 class="mb-0">{{ title }}</h6>
      <p class="text-sm">Last Campaign Performance</p>
    </div>
  </div>
</template>

<script>
export default {
  name: "ChartHolderCard",
  props: {
    title: {
      type: String,
      default: "",
    },
    subtitle: {
      type: String,
      default: "",
    },
    update: {
      type: String,
      default: "",
    },
    color: {
      type: String,
      default: "primary",
    },
  },
};
</script>
