<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View PDF</title>
    <!-- Gunakan versi stabil PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <style>
        #pdf-viewer {
            width: 100%;
            height: 600px;
            overflow: auto;
        }
    </style>
</head>
<body>
    <h1>View PDF Document</h1>
    <p>URL PDF: {{ $pdfPath }}</p> <!-- Debug path PDF -->
    <div id="pdf-viewer"></div>

    <script>
        // URL ke file PDF
        var url = "{{ $pdfPath }}";
        console.log('URL yang dimuat: ', url); // Debug URL

        var pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';

        var loadingTask = pdfjsLib.getDocument(url);
        loadingTask.promise.then(function(pdf) {
            pdf.getPage(1).then(function(page) {
                var scale = 1.5;
                var viewport = page.getViewport({ scale: scale });

                var canvas = document.createElement('canvas');
                var context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                document.getElementById('pdf-viewer').appendChild(canvas);

                var renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                page.render(renderContext);
            });
        }).catch(function(error) {
            console.error('Error loading PDF: ', error);
        });
    </script>
</body>
</html>
