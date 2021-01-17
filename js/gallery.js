/**
 * Manage the WordPress gallery posts.
 */
document.addEventListener('DOMContentLoaded', () => {
  let activeView, activeItem

  const main = document.querySelector('body > main')
  const section = main.querySelector(':scope > section')

  // A gallery is composed by photos and eventual other content (serving as description)
  const photos = section.querySelectorAll('li.blocks-gallery-item')
  let otherContent = section.querySelectorAll(':scope > :not(nav):not(figure.wp-block-gallery)')
  otherContent.length === 0 && (otherContent = null)

  // Elements to activate the different gallery views
  const activators = {}
  main.querySelectorAll('[data-view]').forEach(e => { activators[e.dataset.view] = e })
  !otherContent && (activators.description.remove() || delete (activators.description))

  // Elements to navigate in the gallery items
  const navInItems = {
    prev: section.querySelector('[data-photo="prev"]'),
    next: section.querySelector('[data-photo="next"]')
  }

  function isActiveView (view) {
    return section.classList.contains(view)
  }

  /**
   * Activate one of 3 views of the gallery:
   *   - 'index' view displays all photos, in a grid
   *   - 'gallery' view displays them one by one, as a slideshow
   *   - 'description' view displays the gallery description (ie, the otherContent html elements)
   *
   * @param {string} view Must be 'index', 'gallery' or 'description'
   * @param {boolean} alsoActivateItem If true, call activateItem() on the appropriate item
   */
  const activateView = otherContent
    ? function (view, alsoActivateItem = true) {
        if (view === activeView) return

        activators[activeView]?.classList.remove('active')
        activeView === 'description'
          ? section.classList.remove('gallery') ?? (alsoActivateItem && activateItem(photos[0], false))
          : section.classList.remove(activeView)

        activators[view]?.classList.add('active')
        view === 'description'
          ? section.classList.add('gallery') ?? (alsoActivateItem && activateItem(otherContent, false))
          : section.classList.add(view)

        activeView = view
      }
    // Simpler function if no other content
    : function (view) {
      if (view === activeView) return

      activators[activeView]?.classList.remove('active')
      section.classList.remove(activeView)

      activators[view]?.classList.add('active')
      section.classList.add(view)

      activeView = view
    }

  /**
   * Determine if a given item is a gallery's photo or not.
   */
  function isPhoto (item) {
    return item?.classList?.contains('blocks-gallery-item')
  }

  /**
   * Depending on a given direction, get the direct sibling of the active photo.
   * We considirer the gallery as a loop, ie:
   *   - the last's next is the first
   *   - the first's previous is the last
   *
   * Important: in addition to photos, if the WordPress post has other content, this is consider as the last item.
   *
   * @param {string} direction Must be: prev, next, ArrowLeft or ArrowRight
   */
  const getItemSibling = otherContent
    ? (direction) => (direction === 'prev' || direction === 'ArrowLeft')
        ? (isPhoto(activeItem) ? activeItem.previousElementSibling ?? otherContent : null) ?? photos[photos.length - 1]
        : (isPhoto(activeItem) ? activeItem.nextElementSibling ?? otherContent : null) ?? photos[0]
    // Simpler function if no other content
    : (direction) => (direction === 'prev' || direction === 'ArrowLeft')
        ? activeItem.previousElementSibling ?? photos[photos.length - 1]
        : activeItem.nextElementSibling ?? photos[0]

  /**
   * Activate an item of the photos gallery:
   *   - either a photo (of course)
   *   - or the other content (serving as gallery description)
   *
   * @param {string|HTMLElement|HTMLElement[]} item If item is of type string, get first the associated Element.
   * @param {boolean} alsoActivateView If true, call activateView() with the appropriate view
   */
  const activateItem = otherContent
    ? function (item, alsoActivateView = true) {
        typeof item === 'string' && (item = getItemSibling(item))

        // Hide the current active item
        isPhoto(activeItem)
          ? activeItem.classList.add('invisible')
          : activeItem?.forEach(e => { e.style.display = 'none' })

        // Display the new active item
        isPhoto(item)
          ? item.classList.remove('invisible') ?? (alsoActivateView && activateView('gallery', false))
          : item.forEach(e => { e.style.display = 'block' }) ?? (alsoActivateView && activateView('description', false))

        activeItem = item
      }
    // Simpler function if no other content
    : function (item) {
      typeof item === 'string' && (item = getItemSibling(item))
      activeItem.classList.add('invisible')
      item.classList.remove('invisible')
      activateView('gallery')
      activeItem = item
    }

  // By default, all photos are displayed but invisible (ie, we do not use 'display: none;' css rule).
  // Therefore they are loaded early by the browser.
  photos.forEach(photo => photo.classList.add('invisible'))

  // By default: display the first photo of the gallery view
  activateItem(photos[0])

  // Manage activators for the gallery views
  for (const [view, activator] of Object.entries(activators)) {
    activator.addEventListener('click', e => {
      activateView(view)
      e.preventDefault()
    })
  }

  // Manage the 'click' navigation in the gallery items
  for (const nav of Object.values(navInItems)) {
    nav.addEventListener('click', () => activateItem(nav.dataset.photo))
  }

  // On 'gallery' view only, manage the 'key press' navigation in the gallery items
  document.addEventListener('keyup', (e) => {
    if (isActiveView('gallery') && (e.key === 'ArrowLeft' || e.key === 'ArrowRight')) {
      activateItem(e.key)
    }
  })

  // On 'index' view only, a click on photo displays it in the 'gallery' view
  for (const photo of photos) {
    photo.addEventListener('click', () => {
      if (isActiveView('index')) {
        activateItem(photo)
      }
    })
  }
})
