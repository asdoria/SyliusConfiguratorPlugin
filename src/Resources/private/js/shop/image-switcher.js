class ImageSwitcher {
    constructor (select) {
        this.select = select
        this.stepCode = select.dataset.stepCode
        if (!this.stepCode) return

        this.sliderImages = document.querySelectorAll('.js-image-switcher-image')
         if (!this.sliderImages.length) return

        this.baseImage = Array.from(this.sliderImages).find(img => img.dataset.imageType === this.stepCode)
        if (!this.baseImage) return

        this.srcBaseImage = this.baseImage.src
    }

    processImageSwitcher () {
        this.select.addEventListener('change', () => {
            const selectedOption = this.select.options[this.select.selectedIndex]
            const { imagePath } = selectedOption.dataset

            if (!this.select.value || !imagePath) {
                this.baseImage.src = this.srcBaseImage
                return
            }

            this.baseImage.src = imagePath
        })
    }

    init () {
        this.processImageSwitcher()
    }
}

export default () => {
    const selects = document.querySelectorAll('.js-image-switcher');
    if (!selects.length) return

    selects.forEach(select => {
        const instanceImageSwitcher = new ImageSwitcher(select)
        instanceImageSwitcher.init()
    })
}
