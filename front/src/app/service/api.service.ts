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
}
