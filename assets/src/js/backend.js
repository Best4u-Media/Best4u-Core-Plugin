import '../css/backend.scss'

const { __ } = wp.i18n

const {
	Button,
	Panel,
	PanelBody,
	PanelRow,
	Placeholder,
	Spinner,
	TextControl,
} = wp.components

const { render, Component } = wp.element

const settingsSlugs = ['best4u_core_license_key']

class App extends Component {
	constructor() {
		super(...arguments)

		this.changeOptions = this.changeOptions.bind(this)

		this.state = {
			isAPILoaded: false,
			isAPISaving: false,
		}
	}

	componentDidMount() {
		wp.api.loadPromise.then(() => {
			this.settings = new wp.api.models.Settings()

			if (!this.state.isAPILoaded) {
				this.settings.fetch().then((response) => {
					const savedSettings = {}

					for (const key in response) {
						if (settingsSlugs.includes(key)) {
							savedSettings[key] = response[key]
								? response[key]
								: null
						}
					}

					this.setState({
						isAPILoaded: true,
						...savedSettings,
					})
				})
			}
		})
	}

	changeOptions(option, value) {
		this.setState({
			isAPISaving: true,
		})

		const model = new wp.api.models.Settings({
			[option]: value,
		})

		model.save().then((response) => {
			this.setState({
				[option]: response[option],
				isAPISaving: false,
			})
		})
	}

	render() {
		return (
			<>
				<div className="best4u-core-header">
					<div className="best4u-core-container">
						<div className="best4u-core-header-columns">
							<div className="best4u-core-header-column best4u-core-page-title">
								<h1>{__('Best4u Core', 'best4u-core')}</h1>
							</div>
						</div>
					</div>
				</div>
				{!this.state.isAPILoaded ? (
					<Placeholder>
						<Spinner />
					</Placeholder>
				) : (
					<div className="best4u-core-container">
						<Panel>
							<PanelBody title={__('Settings', 'best4u-core')}>
								<PanelRow>
									<TextControl
										value={
											this.state.best4u_core_license_key
										}
										label={__('License key', 'best4u-core')}
										placeholder={__(
											'License key',
											'best4u-core'
										)}
										disabled={this.state.isAPISaving}
										onChange={(value) => {
											this.setState({
												best4u_core_license_key: value,
											})
										}}
									/>
								</PanelRow>
							</PanelBody>
						</Panel>
						<Panel>
							<PanelBody title={__('Checklist', 'best4u-core')}>
								<PanelRow>test</PanelRow>
							</PanelBody>
						</Panel>
					</div>
				)}
			</>
		)
	}
}

render(<App />, document.getElementById('best4u-core-plugin'))
