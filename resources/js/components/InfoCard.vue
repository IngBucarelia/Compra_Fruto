<template>
  <div class="col-md-6">
    <div class="card border-secondary mb-3">
      <div class="card-header bg-secondary text-white">
        {{ title }}
      </div>
      <div class="card-body">
        <slot>
          <ul v-if="items?.length" class="list-group list-group-flush">
            <li v-for="(item, index) in items" :key="index" class="list-group-item">
              <strong>{{ item.label }}:</strong> {{ item.value }}
            </li>
          </ul>
        </slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    items: Array
  }
}
</script>
