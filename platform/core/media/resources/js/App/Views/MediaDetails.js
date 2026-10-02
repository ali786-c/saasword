import { Helpers } from '../Helpers/Helpers'

export class MediaDetails {
    constructor() {
        this.$detailsWrapper = $('.rv-media-main .rv-media-details')

        this.descriptionItemTemplate = `<div class="mb-3 rv-media-name">
            <label class="form-label">__title__</label>
            __url__
        </div>`

        this.onlyFields = [
            'name',
            'alt',
            'full_url',
            'size',
            'mime_type',
            'created_at',
            'updated_at',
            'nothing_selected',
        ]
    }

    renderData(data) {
        const _self = this
        const thumb =
            data.type === 'image' && data.full_url ? `<img src="${data.full_url}" alt="${data.name || ''}">` : data.icon
        let description = ''
        Helpers.forEach(data, (val, index) => {
            if (Helpers.inArray(_self.onlyFields, index) && val && index !== 'alt') {
                if (!Helpers.inArray(['mime_type'], index)) {
                    description += _self.descriptionItemTemplate.replace(/__title__/gi, Helpers.trans(index)).replace(
                        /__url__/gi,
                        val
                            ? index === 'full_url'
                                ? `<div class="input-group pe-1">
                                        <input type="text" id="file_details_url" class="form-control" value="${val}" />
                                        <button class="input-group-text btn btn-default js-btn-copy-to-clipboard" type="button"
                                                data-bb-toggle="clipboard"
                                                data-clipboard-action="copy"
                                                data-clipboard-message="Copied"
                                                data-clipboard-target="#file_details_url"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-clipboard me-0" data-clipboard-icon="true" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                               <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                               <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"></path>
                                               <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"></path>
                                            </svg>
                                            <svg class="icon text-success me-0 d-none" data-clipboard-success-icon="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                              <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                              <path d="M5 12l5 5l10 -10"></path>
                                            </svg>
                                        </button>
                                    </div>`
                                : `<span title="${val}">${val}</span>`
                            : ''
                    )
                }
            }
        })

        // Add Alt Text input box for media files
        if (data.id) {
            const altVal = data.alt || ''
            const altHtml = `<div class="mb-3 rv-media-alt-box">
                <label class="form-label">${Helpers.trans('alt') || 'Alt Text'}</label>
                <div class="input-group pe-1">
                    <input type="text" id="file_details_alt" class="form-control js-file-details-alt-input" value="${altVal}" placeholder="Alt text for SEO..." data-id="${data.id}" />
                    <button class="btn btn-default input-group-text js-save-file-alt-btn" type="button" data-id="${data.id}" title="Save Alt Text">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy me-0" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                           <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path>
                           <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                           <path d="M14 4l0 4l-6 0l0 -4"></path>
                        </svg>
                    </button>
                </div>
                <span class="js-file-alt-saved-status text-success d-none" style="font-size: 11px; margin-top: 3px; display: block;">Saved!</span>
            </div>`
            description += altHtml
        }

        // Add Watermark toggle switch for images
        const isImage = data.type === 'image' || (data.mime_type && data.mime_type.indexOf('image') !== -1)
        if (data.id && isImage) {
            const hasWatermark = data.options && (data.options.watermark === true || data.options.watermark === 1 || data.options.watermark === '1' || data.options.watermark === 'true')
            const watermarkHtml = `<div class="mb-3 rv-media-watermark-box border-top pt-2">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <label class="form-label mb-0" for="file_details_watermark_toggle" style="font-weight: 600; cursor: pointer;">
                        Watermark
                    </label>
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input js-toggle-file-watermark" type="checkbox" role="switch" id="file_details_watermark_toggle" data-id="${data.id}" ${hasWatermark ? 'checked' : ''}>
                    </div>
                </div>
                <small class="text-muted d-block" style="font-size: 11px;">Toggle to apply watermark to image</small>
            </div>`
            description += watermarkHtml
        }

        _self.$detailsWrapper.find('.rv-media-thumbnail').html(thumb)
        _self.$detailsWrapper.find('.rv-media-thumbnail').css('color', data.color)
        _self.$detailsWrapper.find('.rv-media-description').html(description)

        let dimensions = ''

        if (data.mime_type && data.mime_type.indexOf('image') !== -1) {
            const image = new Image()
            image.src = data.full_url

            image.onload = () => {
                dimensions += this.descriptionItemTemplate
                    .replace(/__title__/gi, Helpers.trans('width'))
                    .replace(/__url__/gi, `<span title="${image.width}">${image.width}px</span>`)

                dimensions += this.descriptionItemTemplate
                    .replace(/__title__/gi, Helpers.trans('height'))
                    .replace(/__url__/gi, `<span title="${image.height}">${image.height}px</span>`)

                _self.$detailsWrapper.find('.rv-media-description').append(dimensions)
            }
        }
    }
}
