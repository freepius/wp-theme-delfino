const pkg = require('./package.json')

module.exports = {
  // Project Identity
  appName: pkg.name,
  type: 'theme',
  slug: pkg.name,
  // Used to generate banners on top of compiled stuff
  bannerConfig: {
    name: pkg.name,
    author: pkg.author,
    license: pkg.license,
    link: pkg.homepage,
    version: pkg.version,
    copyrightText:
      `${pkg.description}\n\nThis software is released under the GPL-3.0 License\nhttps://opensource.org/licenses/GPL-3.0`,
    credit: false
  },
  // Files we need to compile, and where to put
  files: [{
    name: 'app',
    entry: {
      main: './assets/js/main.js',
      'infinite-scroll': './assets/js/infinite-scroll.js',
      gallery: './assets/js/gallery.js'
    }
  }],
  outputPath: 'dist',
  hasReact: false,
  disableReactRefresh: false,
  hasSass: true,
  hasLess: false,
  hasFlow: false,
  // Externals
  // <https://webpack.js.org/configuration/externals/>
  externals: {
    jquery: 'jQuery'
  },
  // Webpack Aliases
  // <https://webpack.js.org/configuration/resolve/#resolve-alias>
  alias: undefined,
  // Show overlay on development
  errorOverlay: true,
  // Auto optimization by webpack
  // Split all common chunks with default config
  // <https://webpack.js.org/plugins/split-chunks-plugin/#optimization-splitchunks>
  // Won't hurt because we use PHP to automate loading
  optimizeSplitChunks: true,
  // Usually PHP and other files to watch and reload when changed
  watch: './inc|includes/**/*.php',
  // Files that you want to copy to your ultimate theme/plugin package
  // Supports glob matching from minimatch
  // @link <https://github.com/isaacs/minimatch#usage>
  packageFiles: [
    'inc/**',
    'vendor/**',
    'dist/**',
    'languages/**',
    'layouts/**',
    '*.php',
    '*.md',
    'readme.txt',
    'LICENSE',
    '*.css',
    '!*.yml'
  ],
  // Path to package directory, relative to the root
  packageDirPath: 'package'
}
