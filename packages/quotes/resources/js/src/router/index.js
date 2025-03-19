import { createRouter, createWebHistory } from 'vue-router'
import AllQuotes from '../views/AllQuotes.vue'
import RandomQuote from '../views/RandomQuote.vue'
import SearchQuote from '../views/SearchQuote.vue'
import QuoteDetail from '../views/QuoteDetail.vue'

const routes = [
  {
    path: '/',
    name: 'all-quotes',
    component: AllQuotes
  },
  {
    path: '/random',
    name: 'random-quote',
    component: RandomQuote
  },
  {
    path: '/search',
    name: 'search-quote',
    component: SearchQuote
  },
  {
    path: '/quote/:id',
    name: 'quote-detail',
    component: QuoteDetail,
    props: true
  }
]

const router = createRouter({
  history: createWebHistory('/quotes-ui'),
  routes
})

export default router 