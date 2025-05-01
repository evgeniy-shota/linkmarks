<div
    class="relative inline-block bg-gray-600 text-gray-100 hover:bg-gray-500 box-border rounded-md w-full px-1 py-1 mx-auto my-1 ">

    <div class="h-20 flex">
        <div class="w-1/4 flex-none overflow-hidden">

            <div class="absolute top-0 left-0">
                <div
                    class="btn btn-sm bg-gray-600 border-gray-600 text-gray-100 shadow-md opacity-50 hover:opacity-100 px-2">
                    <img src="img/icons/box-arrow-up-right.svg">
                </div>
                <div
                    class="btn btn-sm bg-gray-600 border-gray-600 text-gray-100 shadow-md opacity-50 hover:opacity-100 px-2">
                    <img src="img/icons/copy.svg">
                </div>
            </div>

            <div class="flex justify-center items-center h-full">
                <picture>
                    <img src="{{ $logo }}" alt="" class="flex-none">
                </picture>
            </div>
        </div>

        <div class="w-3/4 flex-none overflow-hidden">
            <div class="grid h-full grid-cols-1 content-between">
                <div class="truncate text-lg ">{{ $name }} </div>
                <div class="flex justify-between items-center">
                    <div>

                        <small class="ps-1 rounded-sm bg-gray-600 text-gray-100"> {{ $link }} </small>
                    </div>

                    <div class="btn btn-sm bg-gray-600 border-gray-600 text-gray-100 shadow-md px-2">
                        <img src="img/icons/three-dots-vertical.svg">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
