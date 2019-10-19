process.env.VUE_APP_VERSION = require('./package.json').version

module.exports = {
    publicPath: '/admin',
    outputDir: '../public/admin',
    devServer: {
        host: 'framework.lc',
        allowedHosts: ['framework.lc'],
        port: 7979
    }
}