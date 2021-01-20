/* global fetch, ajaxurl */

/**
 * Load posts in an "infinite scroll" way.
 */
document.addEventListener('DOMContentLoaded', () => {
  let fetchInProgress = false

  function getPosts () {
    return document.querySelectorAll('body.home > main > article')
  }

  if (getPosts()?.length > 0) {
    window.addEventListener('scroll', fetchNextPosts)
  }

  async function fetchNextPosts () {
    // fetch in progress OR not the page bottom
    if (fetchInProgress || (window.innerHeight + window.scrollY) / document.body.clientHeight < 0.8) {
      return
    }

    fetchInProgress = true

    const posts = getPosts()
    const response = await fetch(`${ajaxurl}?action=delfino_infinite_scroll&offset=${posts.length}`)
    const text = await response.text()

    if (response.ok && text) {
      posts[posts.length - 1].insertAdjacentHTML('afterend', text)
      fetchInProgress = false
    } else {
      window.removeEventListener('scroll', fetchNextPosts)
    }
  }
})
