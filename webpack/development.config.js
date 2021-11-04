const base = require('./base.config')

const config = {
	mode: 'development',
	devtool: 'inline-source-map',
	...base,
}

module.exports = config
