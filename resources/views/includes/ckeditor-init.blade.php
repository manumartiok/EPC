<script>
// El selector de 'insertTable' tiene una grilla fija de 10x10. Este botón pide filas y columnas libres.
function ckeditorTablaPersonalizada(editor) {
    editor.ui.componentFactory.add('insertTableCustom', function (locale) {
        // El build CDN no expone ButtonView: se toma el constructor de un botón existente
        const ButtonView = editor.ui.componentFactory.create('undo').constructor;
        const button = new ButtonView(locale);

        button.set({ label: 'Tabla de tamaño personalizado', withText: true, tooltip: true });

        button.on('execute', function () {
            const filas = parseInt(prompt('Cantidad de filas (máx. 100):', '12'), 10);
            if (!filas) return;
            const columnas = parseInt(prompt('Cantidad de columnas (máx. 50):', '12'), 10);
            if (!columnas) return;

            editor.execute('insertTable', {
                rows: Math.min(filas, 100),
                columns: Math.min(columnas, 50)
            });
            editor.editing.view.focus();
        });

        return button;
    });
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.ckeditor').forEach(function (textarea) {
        CKEDITOR.ClassicEditor.create(textarea, {
            extraPlugins: [ckeditorTablaPersonalizada],
            toolbar: {
                items: [
                    'undo', 'redo', '|',
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                    'alignment', '|',
                    'bulletedList', 'numberedList', 'blockQuote', '|',
                    'link', 'insertTable', 'insertTableCustom', 'mediaEmbed', '|',
                    'code', 'codeBlock', 'horizontalLine', 'specialCharacters'
                ],
                shouldNotGroupWhenFull: true
            },
            // 👇 CONFIGURACIÓN ESPECÍFICA PARA TABLAS
            table: {
                contentToolbar: [
                    'tableColumn', 'tableRow', 'mergeTableCells',
                    'tableProperties', 'tableCellProperties'
                ],
                tableProperties: {
                    borderColors: [],
                    backgroundColors: []
                },
                tableCellProperties: {
                    borderColors: [],
                    backgroundColors: []
                }
            },
            // 👇 MANTENER EL FORMATO ORIGINAL
            allowedContent: true,
            autoParagraph: false,
            fillEmptyBlocks: false,
            htmlEncodeOutput: false,
            basicEntities: false,
            entities: false,
            
            fontSize: {
                options: [8, 10, 12, 14, 'default', 18, 24, 36, 48]
            },
            alignment: {
                options: ['left', 'center', 'right', 'justify']
            },
            image: {
                toolbar: [
                    'imageTextAlternative', '|',
                    'imageStyle:inline', 'imageStyle:block', 'imageStyle:side'
                ]
            },
            mediaEmbed: { previewsInData: true },

            removePlugins: [
                'CKBox', 'CKFinder', 'EasyImage',
                'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges',
                'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments',
                'TrackChanges', 'TrackChangesData', 'Pagination', 'WProofreader',
                'SlashCommand', 'Template', 'DocumentOutline', 'FormatPainter',
                'TableOfContents', 'ExportPdf', 'ExportWord', 'ImportWord',
                'AIAssistant', 'MultiLevelList', 'PasteFromOfficeEnhanced',
                'Autoformat', 'ListProperties', 'WordCount'
            ]
        })
        .then(editor => {
            console.log(`✅ CKEditor inicializado correctamente en: ${textarea.name}`);
        })
        .catch(error => {
            console.error(`Error en ${textarea.name}:`, error);
        });
    });
});
</script>