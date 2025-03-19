<template>
  <div>
    <div class="flex items-center mb-4">
      <router-link to="/" class="text-blue-600 hover:text-blue-800 mr-2">
        ← Back to all quotes
      </router-link>
      <h2 class="text-2xl font-semibold ml-2">Quote Details</h2>
    </div>
    
    <div v-if="loading" class="flex justify-center items-center py-10">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
    </div>
    
    <div v-else-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
      {{ error }}
    </div>
    
    <div v-else-if="quote" class="bg-white shadow overflow-hidden sm:rounded-lg">
      <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Quote #{{ quote.id }}
        </h3>
      </div>
      <div class="border-t border-gray-200">
        <dl>
          <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Quote
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 italic">
              "{{ quote.quote }}"
            </dd>
          </div>
          <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
            <dt class="text-sm font-medium text-gray-500">
              Author
            </dt>
            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
              {{ quote.author }}
            </dd>
          </div>
        </dl>
      </div>
    </div>
    
    <div v-else class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
      No quote found with ID {{ id }}.
    </div>
  </div>
</template>

<script>
import QuotesService from '../services/QuotesService'

export default {
  name: 'QuoteDetail',
  props: {
    id: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      quote: null,
      loading: true,
      error: null
    }
  },
  created() {
    this.fetchQuote()
  },
  watch: {
    id(newId) {
      this.fetchQuote(newId)
    }
  },
  methods: {
    fetchQuote() {
      this.loading = true
      QuotesService.getQuote(this.id)
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