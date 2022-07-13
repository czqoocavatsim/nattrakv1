@extends('_layouts.main')
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.js" integrity="sha512-hG/Qw6E14LsVUaQRSgw0RrFA1wl5QPG1a4bCOUgwzkGPIVFsOPUPpbr90DFavEEqFMwFXPVI0NS4MzKsInlKxQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css" integrity="sha512-uf06llspW44/LZpHzHT6qBOIVODjWtv4MxCricRxkzvopAlSWnTf6hpZTFxuuZcuNE9CBQhqE0Seu1CoRk84nQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@section('page')
    <div class="row inside shadow pb-5">
        <div class="col-lg-12 bg-light rounded pt-3 pb-3">
            <p class="header">
                Edit NOTAMs
            </p><hr />
            <textarea id='editor'>{{ $jsonContent }}</textarea>
        </div>
    </div>
    <script>
        var jsonContent = ""
        var editor = CodeMirror.fromTextArea(document.getElementById('editor'), {
            mode: "application/json",
            lineNumbers: true,
        })
        editor.on('changes', (editor) => {
            jsonContent = editor.doc.getValue()
        })
        editor.save()
    </script>
@endsection
