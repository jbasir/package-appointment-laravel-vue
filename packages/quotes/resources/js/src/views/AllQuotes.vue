<template>
  <div>
    <h2 class="text-2xl font-semibold mb-4">All Quotes</h2>
    
    <div v-if="loading" class="flex justify-center items-center py-10">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
    </div>
    
    <div v-else-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
      {{ error }}
    </div>
    
    <div v-else>
      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <quote-card 
          v-for="quote in quotes" 
          :key="quote.id" 
          :quote="quote"
        />
      </div>
    </div>
  </div>
</template>

<script>
import QuotesService from '../services/QuotesService'
import QuoteCard from '../components/QuoteCard.vue'

export default {
  name: 'AllQuotes',
  components: {
    QuoteCard
  },
  data() {
    return {
      quotes: [],
      loading: true,
      error: null
    }
  },
  created() {
    this.fetchQuotes()
  },
  methods: {
    fetchQuotes() {
      this.loading = true
      QuotesService.getAllQuotes()
        .then(response => {
          this.quotes = response.data.data
          this.loading = false
        })
        .catch(error => {
          this.error = 'An error occurred while fetching quotes. Please try again later.'
          console.error(error)
          this.loading = false
        })
    }
  }
}
</script> 