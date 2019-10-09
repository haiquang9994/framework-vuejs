const path = require('path')
function resolve(dir) {
  return path.join(__dirname, dir)
}
process.env.VUE_APP_VERSION = require('./package.json').version

module.exports = {
  chainWebpack(config) {
    config.resolve.alias.set('@', resolve('src'))
  },
  publicPath: '/admin',
  outputDir: '../public/admin',
}