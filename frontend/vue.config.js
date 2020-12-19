process.env.VUE_APP_VERSION = require('./package.json').version

module.exports = {
    publicPath: '/admin',
    outputDir: '../public/admin-tmp',
    devServer: {
        port: 7979
    }
}