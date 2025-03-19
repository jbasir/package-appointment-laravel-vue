<template>
  <div class="quote-card">
    <p class="quote-text">"{{ quote.quote }}"</p>
    <p class="quote-author">— {{ quote.author }}</p>
    <div class="mt-4 text-right" v-if="showViewButton">
      <router-link :to="{ name: 'quote-detail', params: { id: quote.id } }" class="btn btn-primary text-sm">
        View Details
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'QuoteCard',
  props: {
    quote: {
      type: Object,
      required: true
    },
    showViewButton: {
      type: Boolean,
      default: true
    }
  }
}
</script> 