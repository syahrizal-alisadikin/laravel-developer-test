<x-app-layout>
      {{-- session success --}}
      
      
      <div class="py-2">
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
               <div class="bg-green-100 border border-green-400 text-center text-green-700 px-4 py-3 mb-2 rounded relative" role="alert">
                  <strong class="font-bold">{{ session('success') }}</strong>
                 
               </div>
            @endif
            
             <div class="my-4">
                 <a href="{{ route('articles.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">+ Articles</a>
             </div>
             <div class="bg-white">
                 <table class="table-auto w-full">
                     <thead>
                         <tr class="text-center">
                             <th class="border px-2 py-4">No</th>
                             <th class="border px-6 py-4">Title</th>
                             <th class="border px-6 py-4">Content</th>
                             <th class="border px-6 py-4">Image</th>
                             <th class="border px-6 py-4">Author</th>
                             <th class="border px-6 py-4">Action</th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php $i = 1; ?>
                         @forelse ($posts as $item)
                         <tr class="text-center">
                             <td class="border px-6 py-2">{{ $i }}</td>
                             <td class="border px-6 py-2">{{ $item->title }}</td>
                             <td class="border px-6 py-2">{{ $item->content }}</td>
                             <td class="border px-6 py-2"><img src="{{ Storage::url('images/'.$item->image) }}" style="width: 100px" alt=""></td>
                             <td class="border px-6 py-2">{{ $item->user->name }}</td>
                             <td class="border px-6 py-2 text-center">
                              <a href="{{ route('articles.edit', $item->id) }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mx-2 rounded">
                                  Edit
                              </a>
                              <form action="{{ route('articles.destroy', $item->id) }}" method="POST" class="inline-block">
                                  {!! method_field('delete') . csrf_field() !!}
                                  <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 mx-2 rounded inline-block">
                                      Delete
                                  </button>
                              </form>
                          </td>
                         </tr>
                         <?php $i++ ?>
                         @empty
                             <tr>
                                 <td colspan="6" class="text-center font-bold py-4">Data Tidak Ada</td>
                             </tr>
                         @endforelse
                     </tbody>
                 </table>
             </div>
             <div class="text-center mt-5">
                 {{ $posts->links() }}
             </div>
         </div>
</x-app-layout>