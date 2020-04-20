<?php
 
namespace App\Core\Services;


use App\Core\Interfaces\DocumentInterface;
use App\Core\Interfaces\DocumentDownloadInterface;
use App\Core\BaseClasses\BaseService;
use File;


class DocumentService extends BaseService{


    protected $document_repo;
    protected $document_download_repo;


    public function __construct(DocumentInterface $document_repo, DocumentDownloadInterface $document_download_repo){

        $this->document_repo = $document_repo;
        $this->document_download_repo = $document_download_repo;
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

        if (!empty($request->file('doc_file'))) {

            $duplicates_count = 0;

            foreach ($request->file('doc_file') as $data) {

                $file_ext = File::extension($data->getClientOriginalName());
                $file_name = trim($data->getClientOriginalName(), '.'. $file_ext);
                $file_name = $this->__dataType::fileFilterReservedChar($file_name .'-'. $this->str->random(8), '.'. $file_ext);
                $data->storeAs($request->folder_code, $file_name);
                $file_location = $request->folder_code .'/'. $file_name;  

                if($this->document_repo->isFileNameExist($data->getClientOriginalName())){
                    $duplicates_count++;
                    $document = $this->document_repo->store($request, $data, $file_ext, $file_location, 0, 1);
                }else{              
                    $document = $this->document_repo->store($request, $data, $file_ext, $file_location, 0, 0);
                }
                
            }

            if ($duplicates_count > 0) {
                $imported_file = $this->document_repo->getFirstDuplicate();
                $duplicated_file = $this->document_repo->getByFileName($imported_file->file_name);
                $this->event->fire('document.store_has_duplicate', [$imported_file, $duplicated_file]);
            }else{
                $this->event->fire('document.store');
            }

        }

        return redirect()->back();
        
    }






    public function show($slug){

        $document = $this->document_repo->findbySlug($slug);
        return view('guest.document.show')->with('document', $document);

    }






    public function update($request, $slug){

        $document = $this->document_repo->findbySlug($slug);
        $file_name = $document->file_name;
        $file_location = $document->file_location;
        $file_size = $document->file_size;
        $file_ext = $document->file_ext;

        // if doc_file has value
        if(!is_null($request->file('doc_file'))){

            $file_ext = File::extension($request->file('doc_file')->getClientOriginalName());
            $file_name = trim($request->file('doc_file')->getClientOriginalName(), '.'. $file_ext);
            $new_file_name = $this->__dataType::fileFilterReservedChar($file_name .'-'. $this->str->random(8), '.'. $file_ext);
            $old_file_location = $document->file_location;
            $new_file_location = $request->folder .'/'. $new_file_name;
            $file_location = $old_file_location;
            $file_size = $request->file('doc_file')->getSize();

            if ($this->storage->disk('local')->exists($old_file_location)) {
                $this->storage->disk('local')->delete($old_file_location);
            }
            
            $request->file('doc_file')->storeAs($request->folder, $new_file_name);
            $file_location = $new_file_location;
            $file_name = $request->file('doc_file')->getClientOriginalName();

        }elseif($request->folder != $document->folder_name && $this->storage->disk('local')->exists($file_location)){

            $new_file_name = $this->__dataType::fileFilterReservedChar($file_name .'-'. $this->str->random(8), '.'. $file_ext);
            $new_file_location = $request->folder .'/'. $new_file_name;
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




    

    public function download($slug){

        $document = $this->document_repo->findBySlug($slug);

        if(!empty($document->file_location)){
            
            $path = $this->__static->archive_dir() .'/'. $document->file_location;

            if (!File::exists($path)) { return abort(404); }

            $document_download = $this->document_download_repo->store($document);

            $type = File::mimeType($path);
            $header = [
                        'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
                    ];
            $response = response()->download($path, $document->file_name, $header);

            $this->event->fire('document.download');
            return $response;

        }

        return abort(404);
        
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






    public function overwriteReplace($slug){

        $imported_file = $this->document_repo->findbySlug($slug);
        $duplicated_file = $this->document_repo->getByFileName($imported_file->file_name);

        if(!is_null($duplicated_file->file_location)){
            if ($this->storage->disk('local')->exists($duplicated_file->file_location)) {
                $this->storage->disk('local')->delete($duplicated_file->file_location);
            }
        }

        $duplicated_file->delete();

        $this->document_repo->overwriteReplace($imported_file);
        $this->event->fire('document.overwrite_replace', $imported_file);

        $imported_file = $this->document_repo->getFirstDuplicate();

        if (!empty($imported_file)) {
            $duplicated_file = $this->document_repo->getByFileName($imported_file->file_name);  
            $this->event->fire('document.store_has_duplicate', [$imported_file, $duplicated_file]); 
        }else{
            $this->event->fire('document.store', $imported_file);
        }

        return redirect()->back();

    }






    public function overwriteSkip($slug){

        $imported_file = $this->document_repo->findbySlug($slug);

        if(!is_null($imported_file->file_location)){
            if ($this->storage->disk('local')->exists($imported_file->file_location)) {
                $this->storage->disk('local')->delete($imported_file->file_location);
            }
        }

        $imported_file->delete();
        $this->event->fire('document.overwrite', $imported_file);

        $imported_file = $this->document_repo->getFirstDuplicate();

        if (!empty($imported_file)) {
            $duplicated_file = $this->document_repo->getByFileName($imported_file->file_name);  
            $this->event->fire('document.store_has_duplicate', [$imported_file, $duplicated_file]); 
        }else{
            $this->event->fire('document.store', $imported_file);
        }

        return redirect()->back();

    }






    public function overwriteKeepBoth($slug){

        $imported_file = $this->document_repo->findbySlug($slug);
        $duplicated_file = $this->document_repo->getByFileName($imported_file->file_name);

        $file_ext = File::extension($imported_file->file_name);
        $file_name = trim($imported_file->file_name, '.'. $file_ext);
        $new_file_name = $this->__dataType::fileFilterReservedChar($file_name .'-'. $this->carbon->now()->format('Ymd-His'), '.'. $file_ext);

        $this->document_repo->overwriteKeepBoth($imported_file, $new_file_name);
        $this->event->fire('document.overwrite', $imported_file);

        $imported_file = $this->document_repo->getFirstDuplicate();

        if (!empty($imported_file)) {
            $duplicated_file = $this->document_repo->getByFileName($imported_file->file_name);  
            $this->event->fire('document.store_has_duplicate', [$imported_file, $duplicated_file]); 
        }else{
            $this->event->fire('document.store', $imported_file);
        }

        return redirect()->back();        

    }






    public function reports(){

        $documents = $this->document_repo->getAll();

        return view('guest.document.reports')->with('documents', $documents);      

    }






    public function reportsPrint(){

        $documents = $this->document_repo->getAll();

        return view('guest.document.reports_print')->with('documents', $documents);      

    }






}