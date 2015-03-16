namespace CSharpClient
{
    partial class frmMain
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.cmdGetRooms = new System.Windows.Forms.Button();
            this.label1 = new System.Windows.Forms.Label();
            this.label2 = new System.Windows.Forms.Label();
            this.txtLogin = new System.Windows.Forms.TextBox();
            this.txtPassword = new System.Windows.Forms.TextBox();
            this.tblLeaves = new System.Windows.Forms.DataGridView();
            this.label3 = new System.Windows.Forms.Label();
            this.txtBaseURL = new System.Windows.Forms.TextBox();
            this.label4 = new System.Windows.Forms.Label();
            this.Id = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.RoomLocation = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.Manager = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.RoomName = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.Floor = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.Description = new System.Windows.Forms.DataGridViewTextBoxColumn();
            this.cmdIsAvailable = new System.Windows.Forms.Button();
            ((System.ComponentModel.ISupportInitialize)(this.tblLeaves)).BeginInit();
            this.SuspendLayout();
            // 
            // cmdGetRooms
            // 
            this.cmdGetRooms.Location = new System.Drawing.Point(232, 50);
            this.cmdGetRooms.Name = "cmdGetRooms";
            this.cmdGetRooms.Size = new System.Drawing.Size(75, 23);
            this.cmdGetRooms.TabIndex = 0;
            this.cmdGetRooms.Text = "Get Rooms";
            this.cmdGetRooms.UseVisualStyleBackColor = true;
            this.cmdGetRooms.Click += new System.EventHandler(this.cmdGetRooms_Click);
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(19, 58);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(53, 13);
            this.label1.TabIndex = 1;
            this.label1.Text = "Password";
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(19, 31);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(33, 13);
            this.label2.TabIndex = 2;
            this.label2.Text = "Login";
            // 
            // txtLogin
            // 
            this.txtLogin.Location = new System.Drawing.Point(103, 28);
            this.txtLogin.Name = "txtLogin";
            this.txtLogin.Size = new System.Drawing.Size(100, 20);
            this.txtLogin.TabIndex = 3;
            this.txtLogin.Text = "bbalet";
            // 
            // txtPassword
            // 
            this.txtPassword.Location = new System.Drawing.Point(103, 55);
            this.txtPassword.Name = "txtPassword";
            this.txtPassword.PasswordChar = '*';
            this.txtPassword.Size = new System.Drawing.Size(100, 20);
            this.txtPassword.TabIndex = 4;
            this.txtPassword.Text = "bbalet";
            // 
            // tblLeaves
            // 
            this.tblLeaves.AllowUserToAddRows = false;
            this.tblLeaves.AllowUserToDeleteRows = false;
            this.tblLeaves.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize;
            this.tblLeaves.Columns.AddRange(new System.Windows.Forms.DataGridViewColumn[] {
            this.Id,
            this.RoomLocation,
            this.Manager,
            this.RoomName,
            this.Floor,
            this.Description});
            this.tblLeaves.Location = new System.Drawing.Point(16, 77);
            this.tblLeaves.MultiSelect = false;
            this.tblLeaves.Name = "tblLeaves";
            this.tblLeaves.SelectionMode = System.Windows.Forms.DataGridViewSelectionMode.FullRowSelect;
            this.tblLeaves.Size = new System.Drawing.Size(647, 195);
            this.tblLeaves.TabIndex = 5;
            this.tblLeaves.CellDoubleClick += new System.Windows.Forms.DataGridViewCellEventHandler(this.tblLeaves_CellDoubleClick);
            this.tblLeaves.SelectionChanged += new System.EventHandler(this.tblLeaves_SelectionChanged);
            // 
            // label3
            // 
            this.label3.AutoSize = true;
            this.label3.Location = new System.Drawing.Point(19, 8);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(56, 13);
            this.label3.TabIndex = 6;
            this.label3.Text = "Base URL";
            // 
            // txtBaseURL
            // 
            this.txtBaseURL.Location = new System.Drawing.Point(103, 1);
            this.txtBaseURL.Name = "txtBaseURL";
            this.txtBaseURL.Size = new System.Drawing.Size(363, 20);
            this.txtBaseURL.TabIndex = 7;
            this.txtBaseURL.Text = "http://localhost/darany/api/";
            // 
            // label4
            // 
            this.label4.AutoSize = true;
            this.label4.Location = new System.Drawing.Point(16, 287);
            this.label4.Name = "label4";
            this.label4.Size = new System.Drawing.Size(342, 13);
            this.label4.TabIndex = 8;
            this.label4.Text = "Double-click on a row so as to see the timeslots associated to this room";
            // 
            // Id
            // 
            this.Id.HeaderText = "Id";
            this.Id.Name = "Id";
            // 
            // RoomLocation
            // 
            this.RoomLocation.HeaderText = "Location";
            this.RoomLocation.Name = "RoomLocation";
            // 
            // Manager
            // 
            this.Manager.HeaderText = "Manager";
            this.Manager.Name = "Manager";
            // 
            // RoomName
            // 
            this.RoomName.HeaderText = "Name";
            this.RoomName.Name = "RoomName";
            // 
            // Floor
            // 
            this.Floor.HeaderText = "Floor";
            this.Floor.Name = "Floor";
            // 
            // Description
            // 
            this.Description.HeaderText = "Description";
            this.Description.Name = "Description";
            // 
            // cmdIsAvailable
            // 
            this.cmdIsAvailable.Enabled = false;
            this.cmdIsAvailable.Location = new System.Drawing.Point(313, 50);
            this.cmdIsAvailable.Name = "cmdIsAvailable";
            this.cmdIsAvailable.Size = new System.Drawing.Size(75, 23);
            this.cmdIsAvailable.TabIndex = 9;
            this.cmdIsAvailable.Text = "Is Free?";
            this.cmdIsAvailable.UseVisualStyleBackColor = true;
            this.cmdIsAvailable.Click += new System.EventHandler(this.cmdIsAvailable_Click);
            // 
            // frmMain
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(701, 312);
            this.Controls.Add(this.cmdIsAvailable);
            this.Controls.Add(this.label4);
            this.Controls.Add(this.txtBaseURL);
            this.Controls.Add(this.label3);
            this.Controls.Add(this.tblLeaves);
            this.Controls.Add(this.txtPassword);
            this.Controls.Add(this.txtLogin);
            this.Controls.Add(this.label2);
            this.Controls.Add(this.label1);
            this.Controls.Add(this.cmdGetRooms);
            this.Name = "frmMain";
            this.Text = "Darany .net client";
            ((System.ComponentModel.ISupportInitialize)(this.tblLeaves)).EndInit();
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Button cmdGetRooms;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.TextBox txtLogin;
        private System.Windows.Forms.TextBox txtPassword;
        private System.Windows.Forms.DataGridView tblLeaves;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.TextBox txtBaseURL;
        private System.Windows.Forms.Label label4;
        private System.Windows.Forms.DataGridViewTextBoxColumn Id;
        private System.Windows.Forms.DataGridViewTextBoxColumn RoomLocation;
        private System.Windows.Forms.DataGridViewTextBoxColumn Manager;
        private System.Windows.Forms.DataGridViewTextBoxColumn RoomName;
        private System.Windows.Forms.DataGridViewTextBoxColumn Floor;
        private System.Windows.Forms.DataGridViewTextBoxColumn Description;
        private System.Windows.Forms.Button cmdIsAvailable;
    }
}

