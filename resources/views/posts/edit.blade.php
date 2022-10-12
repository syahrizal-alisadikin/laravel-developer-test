<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Article Edit  
        </h2>
    </x-slot>

    <div class="py-12 ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('articles.update', $post->id) }}" class="w-full" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
            <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="flex flex-wrap mx-3 mb-4">
                            <div class="w-full px-3">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                    Title
                                </label>
                                <input value="{{ old('title',$post->title) }}" name="title" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500  @error('title') is-invalid @enderror" id="grid-last-name" type="text" placeholder="Title">
                                @error('title')
                                <span class="invalid-feedback" style="color: red"  role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="flex flex-wrap mx-3 mb-4">
                            <div class="w-full px-3">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                    Content
                                </label>
                                <textarea name="content" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-last-name" type="text" placeholder="Content...">{{ old('content',$post->content) }}</textarea>
                                @error('content')
                                <span class="invalid-feedback" style="color: red"  role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="flex flex-wrap mx-3 mb-4">
                            <div class="w-full px-3">
                                 <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                Image
                                </label>
                                {{-- image --}}
                                <img src="{{Storage::url('images/'. $post->image)}}" class="w-100 mb-3" >
                                <input name="image" class="@error('image') is-invalid @enderror appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-last-name" type="file" placeholder="User Image">
                                @error('image')
                                <span class="invalid-feedback" style="color: red"  role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="flex flex-wrap mx-3 mb-4">
                            <div class="w-full mx-3 ">
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Save Data
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    
            </div>
            </form>
            
        </div>
    </div>
</x-app-layout>