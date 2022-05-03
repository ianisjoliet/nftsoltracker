import {Component, Inject, OnInit} from '@angular/core';
import {ApiService} from "../service/api.service";
import {MatDialogRef, MAT_DIALOG_DATA} from "@angular/material/dialog";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

@Component({
  selector: 'app-dialog',
  templateUrl: './dialog.component.html',
  styleUrls: ['./dialog.component.scss']
})
export class DialogComponent implements OnInit {

  collectionForm !: FormGroup;
  constructor(private formBuilder : FormBuilder,
              private api :ApiService,
              @Inject(MAT_DIALOG_DATA) public editData : any,
              private dialogRef : MatDialogRef<DialogComponent>) {}

  ngOnInit(): void {
    this.collectionForm = this.formBuilder.group({
      collectionName : ['', Validators.required],
      price : ['', Validators.required],
      fees : ['', Validators.required],
    })

    if (this.editData) {
      this.collectionForm.controls['collectionName'].setValue(this.editData.name);
      this.collectionForm.controls['price'].setValue(this.editData.value);
      this.collectionForm.controls['fees'].setValue(this.editData.fees);
    }
  }

  addCollection() {
    if(this.collectionForm.valid) {
      this.api.postCollection(this.collectionForm.value)
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
}
