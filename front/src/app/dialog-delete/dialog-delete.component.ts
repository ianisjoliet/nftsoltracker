import {Component, Inject, OnInit} from '@angular/core';
import {MAT_DIALOG_DATA} from '@angular/material/dialog';
import {ApiService} from "../service/api.service";

@Component({
  selector: 'app-dialog-delete',
  templateUrl: './dialog-delete.component.html',
  styleUrls: ['./dialog-delete.component.scss']
})
export class DialogDeleteComponent implements OnInit {

  constructor(@Inject(MAT_DIALOG_DATA) public data: any, private api :ApiService) { }

  ngOnInit(): void {
  }

  deleteCollection(){
    this.api.deleteCollection(this.data.id)
      .subscribe({
        next:(res)=>{
          window.location.reload();
        },
        error:()=>{
          alert("Error while adding the product")
        }
      })
  }
}
