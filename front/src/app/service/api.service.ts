import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class ApiService {

  constructor(private http: HttpClient) { }

  getCollections() {
    return this.http.get<any>("http://127.0.0.1:8741/api/tracker/collections/all");
  }

  postCollection(data : any) {
    return this.http.post<any>("http://127.0.0.1:8741/api/tracker", data);
  }
  deleteCollection(data : any) {
    console.log(data);
    return this.http.delete<any>("http://127.0.0.1:8741/api/tracker/"+data);
  }
}
