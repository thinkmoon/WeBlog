const path = require('path')
const RemovePlugin = require('remove-files-webpack-plugin')
const CopyWebpackPlugin = require('copy-webpack-plugin')

module.exports = {
  configureWebpack: {
    plugins: [
     new RemovePlugin({
         before: {
             root: path.join(__dirname, './unpackage'),
             include: [
                 path.join(__dirname, 'unpackage/dist', process.env.NODE_ENV === 'production' ?
                     'build' : 'dev', './mp-weixin/sitemap.json')
             ],
             trash: false
         }
     ),
      new CopyWebpackPlugin([{
        from: path.join(__dirname, 'plugins'),
        to: path.join(__dirname, 'unpackage/dist', process.env.NODE_ENV === 'production' ? 'build' : 'dev',
          process.env
          .UNI_PLATFORM, 'plugins')
      }])
    ]
  }
}
