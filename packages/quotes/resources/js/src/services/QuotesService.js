import axios from 'axios'

const apiClient = axios.create({
  baseURL: 'http://127.0.0.1:8000/api/quotes',
  withCredentials: false,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  }
})

export default {
  /**
   * Get all quotes
   * @returns {Promise}
   */
  getAllQuotes() {
    return apiClient.get('/')
  },

  /**
   * Get a random quote
   * @returns {Promise}
   */
  getRandomQuote() {
    return apiClient.get('/random')
  },

  /**
   * Get a specific quote by ID
   * @param {Number} id
   * @returns {Promise}
   */
  getQuote(id) {
    return apiClient.get(`/${id}`)
  }
} 