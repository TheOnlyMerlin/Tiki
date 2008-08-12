/*
 * Morcego - 3D network browser Copyright (C) 2005 Luis Fagundes - Arca
 * <lfagundes@arca.ime.usp.br>
 * 
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as published by the
 * Free Software Foundation; either version 2.1 of the License, or (at your
 * option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License
 * for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with this library; if not, write to the Free Software Foundation,
 * Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */
package br.arca.morcego.structure;


/**
 * @author krico
 * @author lfagundes
 */
public class NodeFactory {
	/**
	 * return default factory instance of node
	 * @author krico
	 * @author lfagundes
	 * @todo This graph should not be passed
	 */
	public static Node createDefaultInstance(String nodeId, Graph g)
	{
		return GraphElementFactory.createNode(nodeId, g);
	}

}
