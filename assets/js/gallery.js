/**
 * Manage the WordPress gallery posts.
 */
document.addEventListener('DOMContentLoaded', () => {
  let activeView, activeItem

  // Element containing all the 'gallery' module and its child-elements
  const main = document.querySelector('body > main')

  // Element containing the gallery (.wp-block-gallery), its description (the 'other content') and the photos navigation
  const section = main.querySelector(':scope > section')

  // Element containing the active photo legend
  const activePhotoLegend = main.querySelector(':scope > header > h2')

  // A gallery is composed by photos and eventual other content (serving as description)
  const photos = section.querySelectorAll('figure.wp-block-image')

  const hasOtherContent = section.querySelectorAll(':scope > :not(nav):not(.wp-block-gallery)').length > 0

  // Elements to activate the different gallery views
  const activators = {}
  main.querySelectorAll('[data-view]').forEach(e => { activators[e.dataset.view] = e })

  // if no other content => delete the 'description' activator
  hasOtherContent || activators.description.parentElement.remove() || delete activators.description

  // Elements to navigate in the gallery items
  const navInItems = {
    prev: section.querySelector('[data-photo="prev"]'),
    next: section.querySelector('[data-photo="next"]')
  }

  function isActiveView (view) {
    return section.classList.contains(view)
  }

  /**
   * Activate one of 3 gallery views:
   *   - index: displays all photos, in a grid
   *   - gallery: displays them one by one, as a slideshow
   *   - description: displays the gallery description
   *
   * @param {string} view Must be: index, gallery or description
   */
  function activateView (view) {
    if (view === activeView) return

    activators[activeView]?.classList.remove('active')
    document.body.classList.remove('gallery-view-' + activeView)
    section.classList.remove(activeView)

    activators[view].classList.add('active')
    document.body.classList.add('gallery-view-' + view)
    section.classList.add(view)

    activeView = view

    // Display the photo legend only on 'gallery' view
    activePhotoLegend.style.display = view === 'gallery' ? 'block' : 'none'

    if (view === 'gallery') {
      // on 'gallery' view, if no active photo => activate the first one
      !isPhoto(activeItem) && activateItem(photos[0])
    } else {
      // on 'description' or 'index' views => reset active item to the gallery description
      setActiveItem(true)
    }
  }

  /**
   * Determine if a given item is a gallery's photo or not.
   */
  function isPhoto (item) {
    return item !== true && item !== undefined
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
   *
   * @return {boolean|HTMLElement} Return TRUE for the desscription (ie, other element) or HTMLElement for a photo
   */
  function getItemSibling (direction) {
    return (direction === 'prev' || direction === 'ArrowLeft')
      ? (isPhoto(activeItem) && (activeItem.previousElementSibling ?? hasOtherContent)) || photos[photos.length - 1]
      : (isPhoto(activeItem) && (activeItem.nextElementSibling ?? hasOtherContent)) || photos[0]
  }

  /**
   * @param {boolean|HTMLElement} item
   */
  function setActiveItem (item) {
    isPhoto(activeItem) && (activeItem.id = '') // Hide current active item
    isPhoto(item) && (item.id = 'individual-image') // Display new one
    activeItem = item
  }

  /**
   * Activate an item of the photos gallery:
   *   - either a photo (of course)
   *   - or the other content (serving as gallery description)
   *
   * @param {string|HTMLElement} item If item is of type string, get first the associated Element.
   */
  function activateItem (item) {
    typeof item === 'string' && (item = getItemSibling(item))

    setActiveItem(item)

    if (isPhoto(item)) {
      activePhotoLegend.innerHTML = item.querySelector('figcaption')?.innerHTML ?? ''
      activateView('gallery')
    } else {
      activateView('description')
    }
  }

  // By default: display the index view
  activateView('index')

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

  // 1. Manage the 'key press' navigation in the gallery items
  // 2. Pressing the escape key activates the 'index' view.
  document.addEventListener('keyup', e =>
    /* 1 */ ((e.key === 'ArrowLeft' || e.key === 'ArrowRight') && !isActiveView('index') && activateItem(e.key)) ||
    /* 2 */ (e.key === 'Escape' && !(e.ctrlKey || e.altKey || e.shiftKey) && activateView('index'))
  )

  // On 'index' view only, a click on photo displays it in the 'gallery' view.
  for (const photo of photos) {
    photo.addEventListener('click', () => isActiveView('index') && activateItem(photo))
  }

  // For a gallery having the special mode "Index view on full page" :
  // a click on the "Back to category" link switches on the index view.
  document
    .querySelector('body.gallery-full-page-index > main > a#back-to-category')
    .addEventListener('click', (e) => e.preventDefault() || activateView('index'))
})
