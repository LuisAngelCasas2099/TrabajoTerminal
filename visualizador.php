<!DOCTYPE html>
<html>
<head>
    <title>Visualizador de PDF</title>
    <style>
        #pdf-viewer {
            width: 100%;
            height: 600px;
            border: 1px solid #000;
            overflow: auto;
        }
        canvas {
            display: block;
            margin: 10px auto;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.13.207/pdf.min.js"></script>
</head>
<body>
    <h1>Visualizador de PDF</h1>
    <div id="pdf-viewer"></div>

    <script>
        // Función para renderizar el PDF
        function renderPDF(url) {
            const loadingTask = pdfjsLib.getDocument(url);
            loadingTask.promise.then(function(pdf) {
                console.log('PDF cargado con éxito');
                
                const pdfViewer = document.getElementById('pdf-viewer');
                pdfViewer.innerHTML = '';

                // Renderizar cada página del PDF
                for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                    pdf.getPage(pageNumber).then(function(page) {
                        const scale = 1.5;
                        const viewport = page.getViewport({ scale: scale });

                        // Preparar el lienzo para dibujar la página PDF
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        // Dibujar la página en el lienzo
                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext).promise.then(function() {
                            console.log('Página ' + pageNumber + ' renderizada con éxito');
                        });

                        // Añadir el lienzo al contenedor del visor
                        pdfViewer.appendChild(canvas);
                    });
                }
            }, function(reason) {
                console.error('Error al cargar el PDF: ' + reason);
            });
        }

        // Llamar a la función para renderizar el PDF (cambia 'uploads/nombre_archivo.pdf' por la ruta a tu archivo PDF)
        renderPDF('C:\Users\luisa\OneDrive\Documentos\Ácidos_Carboxilicos');
    </script>
</body>
</html>