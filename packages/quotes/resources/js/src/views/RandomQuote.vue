<template>
  <div>
    <h2 class="text-2xl font-semibold mb-4">Random Quote</h2>
    
    <div v-if="loading" class="flex justify-center items-center py-10">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
    </div>
    
    <div v-else-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
      {{ error }}
    </div>
    
    <div v-else class="mb-6">
      <quote-card :quote="quote" :show-view-button="false" />
    </div>
    
    <div class="flex justify-center mt-6">
      <button @click="fetchRandomQuote" class="btn btn-primary">
        Get Another Random Quote
      </button>
    </div>
  </div>
</template>

<script>
import QuotesService from '../services/QuotesService'
import QuoteCard from '../components/QuoteCard.vue'

export default {
  name: 'RandomQuote',
  components: {
    QuoteCard
  },
  data() {
    return {
      quote: {},
      loading: true,
      error: null
    }
  },
  created() {
    this.fetchRandomQuote()
  },
  methods: {
    fetchRandomQuote() {
      this.loading = true
      QuotesService.getRandomQuote()
        .then(response => {
          this.quote = response.data.data
          this.loading = false
        })
        .catch(error => {
          this.error = 'An error occurred while fetching a random quote. Please try again later.'
          console.error(error)
          this.loading = false
        })
    }
  }
}
</script> 