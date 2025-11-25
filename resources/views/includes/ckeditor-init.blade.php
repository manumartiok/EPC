<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.ckeditor').forEach(function (textarea) {
        CKEDITOR.ClassicEditor.create(textarea, {
            toolbar: {
                items: [
                    'undo', 'redo', '|',
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                    'alignment', '|',
                    'bulletedList', 'numberedList', 'blockQuote', '|',
                    'link', 'insertTable', 'mediaEmbed', '|',
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