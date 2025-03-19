<template>
  <div>
    <h2 class="text-2xl font-semibold mb-4">Search Quote by ID</h2>
    
    <div class="mb-6">
      <form @submit.prevent="searchQuote" class="flex items-end space-x-4">
        <div class="flex-grow">
          <label for="quoteId" class="block text-sm font-medium text-gray-700 mb-1">Quote ID</label>
          <input 
            type="number" 
            id="quoteId" 
            v-model="quoteId" 
            min="1"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2 border"
            placeholder="Enter a quote ID (e.g., 1, 2, 3...)"
            required
          >
        </div>
        <button type="submit" class="btn btn-primary">
          Search
        </button>
      </form>
    </div>
    
    <div v-if="searched">
      <div v-if="loading" class="flex justify-center items-center py-10">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
      </div>
      
      <div v-else-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
        {{ error }}
      </div>
      
      <div v-else-if="quote && Object.keys(quote).length > 0" class="my-6">
        <quote-card :quote="quote" :show-view-button="false" />
      </div>
      
      <div v-else class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
        No quote found with ID {{ quoteId }}.
      </div>
    </div>
  </div>
</template>

<script>
import QuotesService from '../services/QuotesService'
import QuoteCard from '../components/QuoteCard.vue'

export default {
  name: 'SearchQuote',
  components: {
    QuoteCard
  },
  data() {
    return {
      quoteId: '',
      quote: null,
      loading: false,
      error: null,
      searched: false
    }
  },
  methods: {
    searchQuote() {
      if (!this.quoteId || isNaN(this.quoteId) || this.quoteId < 1) {
        this.error = 'Please enter a valid quote ID (a positive number).'
        this.searched = true
        return
      }
      
      this.loading = true
      this.error = null
      this.searched = true
      
      QuotesService.getQuote(this.quoteId)
        .then(response => {
          this.quote = response.data.data
          this.loading = false
        })
        .catch(error => {
          if (error.response && error.response.status === 404) {
            this.quote = null
          } else {
            this.error = 'An error occurred while fetching the quote. Please try again later.'
            console.error(error)
          }
          this.loading = false
        })
    }
  }
}
</script> 