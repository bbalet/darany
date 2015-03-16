using System;

namespace CSharpClient
{
    /// <summary>
    /// Availability Object
    /// </summary>
    public class Availability
    {
        public bool IsFree { get; set; }
        public DateTime? NextState { get; set; }    //API can return a NULL value
    }
}
