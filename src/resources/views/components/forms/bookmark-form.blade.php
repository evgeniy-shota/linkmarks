<div class="flex justify-center items-center">
    <div class="rounded-md bg-gray-600 px-4 py-3">

        <form action="" method="post">
            @csrf
            <fieldset class="fieldset text-gray-100">
                <legend class="fieldset-legend text-gray-100 after:content-['*']">Link</legend>
                <input type="text" class="input bg-gray-700" placeholder="www.youtube.com" />
                <p class="label">Enter link</p>
            </fieldset>

            <fieldset class="fieldset text-gray-100">
                <legend class="fieldset-legend text-gray-100 after:content-['*']">Name</legend>
                <input type="text" class="input bg-gray-700" minlength="3" placeholder="You tube" />
                <p class="label">Enter bookmark name</p>
            </fieldset>

            <fieldset class="fieldset">
                <legend class="fieldset-legend text-gray-100">Thumbnail</legend>
                <input type="file"
                    class="file:bg-gray-500 file:border-1 file:border-gray-600 file:btn file:text-gray-100 hover:file:border-gray-500 file:rounded-sm cursor-pointer file:py-3 file:px-4 file:me-3 rounded-sm bg-gray-700 text-gray-100" />
                <label class="label text-gray-100">Max size 2MB</label>
            </fieldset>

            <div class="flex justify-around items-center mt-2">
                <button class="btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md">
                    Clear
                </button>

                <button type="submit"
                    class="btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md">
                    Submit
                </button>
            </div>
        </form>

    </div>
</div>
