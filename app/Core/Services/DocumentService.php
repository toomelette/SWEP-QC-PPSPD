<?php
 
namespace App\Core\Services;


use App\Core\Interfaces\DocumentInterface;
use App\Core\BaseClasses\BaseService;
use File;


class DocumentService extends BaseService{


    protected $document_repo;



    public function __construct(DocumentInterface $document_repo){

        $this->document_repo = $document_repo;
        parent::__construct();

    }





    public function fetch($request){

        $documents = $this->document_repo->fetch($request);

        $request->flash();
        return view('guest.document.index')->with('documents', $documents);

    }





    public function fetchArchives($request){

        $documents = $this->document_repo->fetchDeleted($request);

        $request->flash();
        return view('guest.document.archives')->with('documents', $documents);

    }






    public function store($request){

        dd($request->file('doc_file'));

        $file_location = "";
        $filename = trim($request->file('doc_file')->getClientOriginalName(), '.pdf');

        if(!is_null($request->file('doc_file'))){

            $filename = $this->__dataType::fileFilterReservedChar($filename .'-'. $this->str->random(8), '.pdf');
            $dir = $request->folder;
            $request->file('doc_file')->storeAs($dir, $filename);
            $file_location = $dir .'/'. $filename;

        }
            

        $document = $this->document_repo->store($request, $file_location);
        
        $this->event->fire('document.store');
        return redirect()->back();
    }






    public function edit($slug){

        $document = $this->document_repo->findbySlug($slug);
        return view('guest.document.edit')->with('document', $document);

    }







    public function update($request, $slug){
        
        $document = $this->document_repo->findbySlug($slug);
        $file_name = $document->file_name;
        $file_location = $document->file_location;
        $file_size = $document->file_size;

        // if doc_file has value
        if(!is_null($request->file('doc_file'))){

            $file_name = trim($request->file('doc_file')->getClientOriginalName(), '.pdf');
            $new_filename = $this->__dataType::fileFilterReservedChar($file_name .'-'. $this->str->random(8), '.pdf');
            $dir = $request->folder;
            $old_file_location = $document->file_location;
            $new_file_location = $dir .'/'. $new_filename;
            $file_location = $old_file_location;
            $file_size = $$request->file('doc_file')->getSize();

            if ($this->storage->disk('local')->exists($old_file_location)) {
                $this->storage->disk('local')->delete($old_file_location);
            }
            
            $request->file('doc_file')->storeAs($dir, $new_filename);
            $file_location = $new_file_location;

        }elseif($request->folder != $document->folder_name && $this->storage->disk('local')->exists($file_location)){

            $new_filename = $this->__dataType::fileFilterReservedChar($file_name .'-'. $this->str->random(8), '.pdf');
            $dir = $request->folder;
            $new_file_location = $dir .'/'. $new_filename;

            $this->storage->disk('local')->move($file_location, $new_file_location);
            $file_location = $new_file_location;
        }

        $document = $this->document_repo->update($request, $file_name, $file_size, $file_location, $document);

        $this->event->fire('document.update', $document);
        return redirect()->route('guest.document.index');

    }





    public function viewFile($slug){

        $document = $this->document_repo->findBySlug($slug);

        if(!empty($document->file_location)){

            $path = $this->__static->archive_dir() .'/'. $document->file_location;

            if (!File::exists($path)) { return "Cannot Detect File!"; }

            $file = File::get($path);
            $type = File::mimeType($path);

            $response = response()->make($file, 200);
            $response->header("Content-Type", $type);

            return $response;

        }

        return "Cannot Detect File!";;
        

    }






    public function destroy($slug){

        $document = $this->document_repo->destroy($slug);

        $this->event->fire('document.destroy', $document);
        return redirect()->back();

    }






    public function destroyHard($slug){

        $document = $this->document_repo->findbySlug($slug);

        if(!is_null($document->file_location)){

            if ($this->storage->disk('local')->exists($document->file_location)) {
                $this->storage->disk('local')->delete($document->file_location);
            }

        }

        $document = $this->document_repo->destroyHard($document);

        $this->event->fire('document.destroy', $document);
        return redirect()->back();

    }






    public function restore($slug){

        $document = $this->document_repo->restore($slug);

        $this->event->fire('document.restore', $document);
        return redirect()->back();

    }






}