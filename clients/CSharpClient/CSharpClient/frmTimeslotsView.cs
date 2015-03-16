using System;
using System.Windows.Forms;
using System.Collections.Generic;
using System.Collections.Specialized;

namespace CSharpClient
{
    public partial class frmTimeslotsView : Form
    {
        /// <summary>
        /// Initialize the form and fill the datagrid with the list of timeslots
        /// </summary>
        /// <param name="p_objLeave">Leave Object retrieved through REST)</param>
        public frmTimeslotsView(List<Timeslot> p_lstTimeslots)
        {
            InitializeComponent();
            this.tblTimeslots.Rows.Clear();
            foreach (Timeslot l_objTimeslot in p_lstTimeslots)
            {
                tblTimeslots.Rows.Add(l_objTimeslot.Id,
                    l_objTimeslot.StartDate,
                    l_objTimeslot.EndDate,
                    l_objTimeslot.status_name,
                    l_objTimeslot.creator_name,
                    l_objTimeslot.note);
            }
        }

        /// <summary>
        /// Close the windows when click on leave button
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void cmdClose_Click(object sender, EventArgs e)
        {
            Close();
        }
    }
}
