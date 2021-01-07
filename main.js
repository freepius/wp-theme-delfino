document.addEventListener('DOMContentLoaded', () => {
  handleToggleMainMenu()

  makeOnlyOneChildPerH2()

  const allH2 = document.querySelectorAll('main.collapser > h2')

  // collapse on <h2> click
  allH2.forEach(h2 => h2.addEventListener('click', e =>
    allH2.forEach(h2 => e.target !== h2
      ? h2.classList.remove('active')
      : document.documentElement.clientWidth > 850 ? h2.classList.add('active') : h2.classList.toggle('active')
    )
  ))
})

/**
 * Manage the "toggle" button of the main menu
 */
function handleToggleMainMenu () {
  const mainMenu = document.getElementById('main-menu')
  const toggler = document.querySelector('body > nav > button[aria-controls="main-menu"]')

  toggler.addEventListener('click', () => {
    mainMenu.classList.toggle('active')
    toggler.setAttribute('aria-expanded', toggler.getAttribute('aria-expanded') === 'true' ? 'false' : 'true')
  })
}

/**
 * If a <h2> element has several children, group them in a <section> element.
 */
function makeOnlyOneChildPerH2 () {
  let h2 = document.querySelector('main.collapser > h2:first-of-type')
  let e = h2.nextElementSibling
  let section = document.createElement('section')

  const flushSection = function (h2, section) {
    if (section.children.length > 0) {
      h2.insertAdjacentElement('afterend', section.children.length > 1 ? section : section.children[0])
    }
  }

  // make the first <h2> & child-element active
  h2.classList.add('active')

  while (e) {
    if (e.nodeName === 'H2') {
      // new <h2> found -> flush the current <section>
      flushSection(h2, section)

      h2 = e
      e = e.nextElementSibling
      section = document.createElement('section')
    } else {
      const next = e.nextElementSibling
      section.appendChild(e)
      e = next
    }
  }

  // flush the last <section>
  flushSection(h2, section)
}
