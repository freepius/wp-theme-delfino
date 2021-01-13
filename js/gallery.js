/**
 * Manage the gallery and index views for the WordPress gallery posts.
 */
document.addEventListener('DOMContentLoaded', () => {
  let currentView, activePhoto

  function isIndexView () {
    return section.classList.contains('index')
  }

  function activateView (view) {
    activators[currentView]?.classList.remove('active')
    section.classList.remove(currentView)

    activators[view]?.classList.add('active')
    section.classList.add(view)
    currentView = view
  }

  function activatePhoto (photo) {
    activePhoto?.classList.remove('active')
    photo?.classList.add('active')
    activePhoto = photo
  }

  const main = document.querySelector('body > main')
  const section = main.querySelector(':scope > section')
  const photos = section.querySelectorAll('li.blocks-gallery-item')
  const activators = {
    gallery: main.querySelector(':scope > header > nav > a[data-view="gallery"]'),
    index: main.querySelector(':scope > header > nav > a[data-view="index"]')
  }
  const navInPhotos = {
    prev: section.querySelector(':scope > nav > a[data-photo="prev"]'),
    next: section.querySelector(':scope > nav > a[data-photo="next"]')
  }

  // by default: display the first photo of the gallery view
  activatePhoto(photos[0])
  activateView('gallery')

  for (const activator of Object.values(activators)) {
    activator.addEventListener('click', () => {
      activateView(activator.dataset.view)
    })
  }

  for (const nav of Object.values(navInPhotos)) {
    const getPhoto = nav.dataset.photo === 'prev'
      ? () => activePhoto.previousElementSibling || photos[photos.length - 1] // if no previous photo -> go to the last one
      : () => activePhoto.nextElementSibling || photos[0] // if no next photo -> go the the first one

    nav.addEventListener('click', () => activatePhoto(getPhoto()))
  }

  for (const photo of photos) {
    photo.addEventListener('click', () => {
      if (isIndexView()) {
        activatePhoto(photo)
        activateView('gallery')
      }
    })
  }
})
