<?php
 
namespace App\Core\Services;


use App\Core\Interfaces\DocumentInterface;
use App\Core\BaseClasses\BaseService;


class DocumentService extends BaseService{


    protected $document_repo;



    public function __construct(DocumentInterface $document_repo){

        $this->document_repo = $document_repo;
        parent::__construct();

    }





    public function fetchGuest($request){

        $documents = $this->document_repo->fetchGuest($request);

        $request->flash();
        return view('guest.document.index')->with('documents', $documents);

    }





    // public function fetch($request){

    //     $documents = $this->document_repo->fetch($request);

    //     $request->flash();
    //     return view('dashboard.document.index')->with('documents', $documents);

    // }






    public function store($request){

        $file_location = "";
        $filename =trim($request->file('doc_file')->getClientOriginalName(), '.pdf');

        if(!is_null($request->file('doc_file'))){

            $filename = $this->__dataType::fileFilterReservedChar($filename .'-'. $this->str->random(8), '.pdf');
            $dir = '/DOCUMENTS';
            $request->file('doc_file')->storeAs($dir, $filename);
            $file_location = $dir .'/'. $filename;

        }
            

        $document = $this->document_repo->store($request, $file_location);
        
        $this->event->fire('document.store');
        return redirect()->back();
    }







    // public function viewFile($slug){

    //     $document = $this->document_repo->findBySlug($slug);

    //     if(!empty($document->file_location)){

    //         $path = $this->__static->archive_dir() .'/'. $document->file_location;

    //         if (!File::exists($path)) { return "Cannot Detect File!"; }

    //         $file = File::get($path);
    //         $type = File::mimeType($path);

    //         $response = response()->make($file, 200);
    //         $response->header("Content-Type", $type);

    //         return $response;

    //     }

    //     return "Cannot Detect File!";;
        

    // }






    // public function edit($slug){

    //     $document = $this->document_repo->findbySlug($slug);
    //     return view('dashboard.document.edit')->with('document', $document);

    // }







    // public function update($request, $slug){

    //     $document = $this->document_repo->findbySlug($slug);
        
    //     $new_filename = $this->__dataType::fileFilterReservedChar($request->title .'-'. $this->str->random(8), '.pdf');
    //     $dir = $this->__dataType->date_parse($this->carbon->now(), 'Y') .'/APPLICATION-FORMS';

    //     $old_file_location = $document->file_location;
    //     $new_file_location = $dir .'/'. $new_filename;

    //     $file_location = $old_file_location;

    //     // if doc_file has value
    //     if(!is_null($request->file('doc_file'))){

    //         if ($this->storage->disk('local')->exists($old_file_location)) {
    //             $this->storage->disk('local')->delete($old_file_location);
    //         }
            
    //         $request->file('doc_file')->storeAs($dir, $new_filename);
    //         $file_location = $new_file_location;

    //     // if title has change
    //     }elseif($request->title != $document->title && $this->storage->disk('local')->exists($old_file_location)){
    //         $this->storage->disk('local')->move($old_file_location, $new_file_location);
    //         $file_location = $new_file_location;
    //     }

    //     $document = $this->document_repo->update($request, $file_location, $document);

    //     $this->event->fire('document.update', $document);
    //     return redirect()->route('dashboard.document.index');

    // }






    // public function destroy($slug){

    //     $document = $this->document_repo->findbySlug($slug);

    //     if(!is_null($document->file_location)){

    //         if ($this->storage->disk('local')->exists($document->file_location)) {
    //             $this->storage->disk('local')->delete($document->file_location);
    //         }

    //     }

    //     $document = $this->document_repo->destroy($document);

    //     $this->event->fire('document.destroy', $document);
    //     return redirect()->back();

    // }






}