<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <a href="{{route('index')}}">Pages</a>
        </h2>
    </x-slot>

    <div class="py-10 flex space-x-1">
        <div class="w-fit mx-40">
            <div>
                <div>
                    <input type="text" placeholder="update" id="update" hidden>
                    <input type="text" placeholder="tittle" id="updateurl" hidden>
                </div>
                <form id="pagesform" class="bg-slate-300 px-8 py-4  flex flex-col space-y-4 font-serif" enctype="multipart/form-data">

                    <div>
                        <div id="imgupdiv" class=" uploadimg cursor-pointer rounded-full bg-white border-4 border-black border-dotted flex justify-center items-center h-32 text-center w-32 ml-16">
                            <svg xmlns="http://www.w3.org/2000/svg" height="40" viewBox="0 -960 960 960" width="40" id="uploadsvg">
                                <path d="M440-440ZM120-120q-33 0-56.5-23.5T40-200v-480q0-33 23.5-56.5T120-760h126l74-80h240v80H355l-73 80H120v480h640v-360h80v360q0 33-23.5 56.5T760-120H120Zm640-560v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80ZM440-260q75 0 127.5-52.5T620-440q0-75-52.5-127.5T440-620q-75 0-127.5 52.5T260-440q0 75 52.5 127.5T440-260Zm0-80q-42 0-71-29t-29-71q0-42 29-71t71-29q42 0 71 29t29 71q0 42-29 71t-71 29Z" />
                            </svg>
                            <img src="" width="100px" height="100px" class="w-full rounded-full" id="showimg">
                        </div>

                    </div>
                    <div>
                        <div id="imguploadbtn" class="uploadimg cursor-pointer bg-blue-600 text-white text-center py-2  border rounded-md flex px-16 space-x-4 hover:text-black">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24" class="fill-white uploadbtnpart">
                                <path d="M440-320v-326L336-542l-56-58 200-200 200 200-56 58-104-104v326h-80ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z" />
                            </svg>
                            <h1 class="uploadbtnpart">Upload Image</h1>
                        </div>
                        <input type="file" placeholder="img" name="img" id="filetypeimg" hidden>
                        <p class="text-red-600 ml-8 mt-2 font-serif" id="imgerror"></p>
                    </div>
                    <div>
                        <input type="text" placeholder="Tittle" class="border rounded-md w-full" name="tittle" id="title">
                        <p class="text-red-600 ml-8 mt-2 font-serif" id="titleerror"> </p>

                    </div>

                    <button type=" submit" id="btn" class="bg-blue-600 px-4 text-white py-4 rounded-md font-bold text-xl hover:bg-black hover:duration-700">Add</button>
                </form>
            </div>
        </div>
        <div>
            <table class="font-serif">
                <thead>
                    <tr class="border border-black">
                        <th class="px-8 border border-black font-extrabold">
                            Sn
                        </th>
                        <th class="px-8 border border-black font-extrabold">
                            Tittle
                        </th>
                        <th class="px-8 border border-black font-extrabold">
                            Image
                        </th>
                        <th class="px-8 ">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    @forelse ($pagesdata as $key=> $page)
                    <tr id="row_{{$page->id}}" class="border border-black ">
                        <td class="px-8" id="key_{{$page->id}}">{{$key+1}} </td>
                        <td id="tittle_{{$page->id}}" class="px-8 border border-black">
                            {{$page->tittle}}
                        </td>

                        <td id="img_{{$page->id}}" class="px-8 py-4 border border-black">
                            <img src="{{asset('/storage/uploads/' . $page->img)}}" width="100px" height="100px" id="rowimg_{{$page->id}}">
                        </td>

                        <td class="px-8">
                            <button class="edits px-4 font-extrabold border rounded-md py-4 bg-blue-600 text-white" data-id="{{$page->id}}">Edit</button>
                            <button class=" delete px-4 py-4  bg-red-600 text-white font-bold rounded-md" data-id="{{$page->id}}">Delete</button>
                        </td>
                    </tr>


                    @empty
                    <tr id="norec">
                        <td colspan="8" class="border border-black text-center py-8">
                            "no Records Founds"
                        </td>

                    </tr>

                    @endforelse

                </tbody>
            </table>

        </div>

    </div>

</x-app-layout>
<script>
    $(document).ready(function() {
        $('#showimg').hide()

        $('.uploadimg').on('click', function() {
            $('#filetypeimg').click();

        });

        $('#filetypeimg').on('change', function() {
            const imgfile = this.files[0];
            if (imgfile) {
                const reader = new FileReader();
                reader.onload = function() {
                    $('#uploadsvg').hide();
                    const result = reader.result;
                    $('#imgupdiv').removeClass("justify-center items-center ");
                    $('#showimg').attr('src', result);
                    $('#showimg').show();
                }
                reader.readAsDataURL(imgfile);
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $('#update').val('add');
        $(document).on('click', '.edits', function() {
            $('#update').val('update');
        });

        $('#pagesform').on('submit', function(e) {
            var tbody = $('#tbody');
            var url = '';
            if ($('#update').val() == "add") {
                url = "{{route('add')}}";
            }
            if ($('#update').val() == "update") {
                url = $('#updateurl').val();
            }
            e.preventDefault();
            $.ajax({
                url: url,
                method: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#titleerror').html(' ');
                    $('#imgerror').html(' ');
                    $('#norec').hide();
                    if ($('#update').val() == "add") {
                        var tr = `<tr id="row_${response.createdData.id}" class="border border-black"><td class="px-8 border border-black">${response.index}</td>
                        <td id="tittle_${response.createdData.id}" class="px-8 border border-black">${response.createdData.tittle}</td><td class="px-8 py-4 border border-black">
                        <img id="rowimg_${response.createdData.id}"  src="${response.createdData.img}" width="100px" height="100px">
                        </td> <td class="px-8 border py-4 border-black"> <button class="edits px-4 font-extrabold border rounded-md py-4 bg-blue-600 text-white" data-id="${response.createdData.id}">Edit</button>
                        <button class="delete px-4 py-4  bg-red-600 text-white font-bold rounded-md" data-id="${response.createdData.id}">Delete</button>
                        </td>
                        </tr>`;
                        tbody.append(tr);



                    }

                    if ($('#update').val() == "update") {
                        $('#tittle_' + response.id).html(response.updatedData.tittle);
                        $('#rowimg_' + response.id).removeAttr('src');
                        $('#rowimg_' + response.id).attr('src', response.updatedData.img);
                        $('#update').val('add');

                    }
                    formReset();
                    swal("Sucessfully!", 'Page  ' + response.action, 'success')

                },

                error: function(error) {
                    var errorList = error.responseJSON.errors;
                    errormsg(errorList);
                }

            })
        })

        function errormsg(errorList) {
            errorList.tittle ? $('#titleerror').html(errorList.tittle) : $('#titleerror').html(' ')
            errorList.img ? $('#imgerror').html(errorList.img) : $('#imgerror').html(' ');
        }

        function formReset() {
            $('#pagesform')[0].reset();
            $('#imgupdiv').addClass("justify-center items-center");
            $('#uploadsvg').show();
            $('#showimg').hide();
        }

        $(document).on('click', '.edits', function() {
            let id = $(this).data('id');
            let editurl = "{{route('edit','id')}}";
            editurl = editurl.replace('id', id);
            let updateurl = "{{route('update','id')}}";
            updateurl = updateurl.replace('id', id);
            $('#btn').html("Save Changes");
            $('#updateurl').val(updateurl);
            $.ajax({
                url: editurl,
                method: "GET",
                success: function(response) {
                    $('#title').val(response.tittle);
                    $('#slug').val(response.slug);
                    $('#uploadsvg').hide();
                    $('#imgupdiv').removeClass("justify-center items-center ");
                    $('#showimg').attr('src', response.img);
                    $('#showimg').show();
                },
                error: function(error) {

                }
            })
        })

        $(document).on('click', '.delete', function() {
            let id = $(this).data('id');
            let deleteurl = "{{route('delete','id')}}";
            deleteurl = deleteurl.replace('id', id);
            $.ajax({
                url: deleteurl,
                method: 'GET',
                success: function(response) {
                    $('#row_' + response.id).remove();
                    swal("Sucessfully!", 'Page  ' + response.action, 'success')

                },
                error: function(error) {

                }

            });


        });

    });
</script>