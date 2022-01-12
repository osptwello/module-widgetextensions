define([
    'Magento_Ui/js/form/element/abstract',
    'underscore',
    'mage/adminhtml/wysiwyg/widget',
    'mage/translate'
], function (Abstract, _) {

    window.widgets = [];

    window.WysiwygWidget.Widget.prototype.initialize = function(formEl, widgetEl, widgetOptionsEl, optionsSourceUrl, widgetTargetId) {
        $(formEl).insert({bottom: widgetTools.getDivHtml(widgetOptionsEl)});
        jQuery('#' + formEl).mage('validation', {
            ignore: ".skip-submit",
            errorClass: 'mage-error'
        });
        this.formEl = formEl;
        this.widgetEl = $(widgetEl);
        this.widgetOptionsEl = $(widgetOptionsEl);
        this.optionsUrl = optionsSourceUrl;
        this.optionValues = new Hash({});
        this.widgetTargetId = widgetTargetId;
        if (typeof(tinyMCE) != "undefined" && tinyMCE.activeEditor) {
            this.bMark = tinyMCE.activeEditor.selection.getBookmark();
        }

        Event.observe(this.widgetEl, "change", this.loadOptions.bind(this));

        this.initOptionValues();

        window.widgets[widgetTargetId] = this;
    };

    window.WysiwygWidget.Widget.prototype.initOptionValues = function() {
        var widgetCode = jQuery('#' + this.widgetTargetId).val();

        if (widgetCode.indexOf('{{widget') != -1) {
            this.optionValues = new Hash({});
            widgetCode.gsub(/([a-z0-9\_]+)\s*\=\s*[\"]{1}([^\"]+)[\"]{1}/i, function(match){
                if (match[1] == 'type') {
                    this.widgetEl.value = match[2];
                } else {
                    this.optionValues.set(match[1], match[2]);
                }
            }.bind(this));

            jQuery('#' + this.widgetTargetId).val('');

            this.loadOptions();
        }
    };

    return Abstract.extend({
        /** Define variables passed to KO here */
        defaults: {
            items: [],
            saved: true
        },

        /**
         * Initializes component, invokes initialize method of Abstract class.
         *
         *  @returns {Object} Chainable.
         */
        initialize: function () {
            var valueObject = [];

            this._super();

            if(this.value()){
                try {
                    valueObject = JSON.parse(this.value());
                } catch(e) {
                    debugger;
                    console.error('Widgets JSON seems to be incorrect, check attribute: category_widgets');
                }
            }

            this.items(valueObject);

            this.saveItemsData();

            return this;
        },

        addNewRow: function(){
            var items = this.items();
            items.push({column: '', widget: ''});

            this.items(items);

            this.saved(false);
        },

        saveItemsData: function () {
            this.items(this.items());

            _.each(this.items(), function(item, index){
                jQuery('.widget-holder-cell.item-' + index + ' iframe').attr('src', window.widgetRendererUrl + '/widget/' + encodeURIComponent(btoa(item.widget)));
            });

            this.saved(true);
        },

        openWidgetDialog: function(id){
            window.widgetTools.openDialog(window.widgetWindowUrl + 'widget_target_id/' + id);
        },

        removeItemRow: function(rowId, that) {
            that.items().splice(rowId, 1);
            that.items(that.items());
        },

        /**
         * Init observables
         *
         * @returns {Object} Chainable.
         */
        initObservable: function () {
            var parent = this;

            this._super()
                .observe([
                    'items',
                    'saved'
                ]);

            function updateValue() {
                if(parent.items) {
                    parent.value(JSON.stringify(parent.items()));
                }
            }

            this.items.subscribe(updateValue);

            return this;
        }
    });
});
